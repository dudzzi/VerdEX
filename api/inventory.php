<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json; charset=UTF-8");

require_once "../config.php";

$action = $_POST["action"] ?? $_GET["action"] ?? "";

function sendResponse($success, $message = "", $extra = [])
{
    echo json_encode(
        array_merge(
            [
                "success" => $success,
                "message" => $message
            ],
            $extra
        )
    );

    exit;
}


/* CATALOG */

if ($action === "catalog") {

    $catalog = [
        "plant" => [],
        "fertilizer" => [],
        "tool" => []
    ];

    $result = $conn->query(
        "SELECT * FROM plant_catalog ORDER BY name ASC"
    );

    if (!$result) {
        sendResponse(false, "Plant catalog query failed: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $catalog["plant"][] = $row;
    }


    $result = $conn->query(
        "SELECT * FROM fertilizer_catalog ORDER BY name ASC"
    );

    if (!$result) {
        sendResponse(false, "Fertilizer catalog query failed: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $catalog["fertilizer"][] = $row;
    }


    $result = $conn->query(
        "SELECT * FROM tool_catalog ORDER BY name ASC"
    );

    if (!$result) {
        sendResponse(false, "Tool catalog query failed: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $catalog["tool"][] = $row;
    }


    sendResponse(
        true,
        "Catalog loaded.",
        [
            "catalog" => $catalog
        ]
    );
}


/* GET INVENTORY */

if ($action === "get") {

    $result = $conn->query(
        "
        SELECT
            i.inventory_id AS id,
            i.catalog_id,

            CASE
                WHEN c.category_name = 'Hydroponic Plant'
                    THEN 'plant'
                WHEN c.category_name = 'Fertilizer'
                    THEN 'fertilizer'
                WHEN c.category_name = 'Tool'
                    THEN 'tool'
                ELSE ''
            END AS item_type,

            i.item_name AS name,
            i.picture AS image_path,
            i.quantity AS stock,
            i.unit,
            i.notes,
            i.is_active,
            i.created_at AS date_added,
            i.updated_at AS updated_at,

            (
                SELECT MAX(t.created_at)
                FROM inventory_transactions t
                WHERE t.inventory_id = i.inventory_id
                AND t.transaction_type = 'IN'
            ) AS last_added

        FROM inventory_items i

        INNER JOIN inventory_categories c
            ON i.category_id = c.category_id

        WHERE i.is_active = 1

        ORDER BY i.created_at DESC
        "
    );

    if (!$result) {
        sendResponse(
            false,
            "Inventory query failed: " . $conn->error
        );
    }

    $items = [];

    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }

    sendResponse(
        true,
        "Inventory loaded.",
        [
            "items" => $items
        ]
    );
}


/* ADD INVENTORY ITEM */

if ($action === "add") {

    $type = trim($_POST["item_type"] ?? "");

    $catalogId = intval($_POST["catalog_id"] ?? 0);

    $stock = floatval($_POST["stock"] ?? 0);

    $unit = trim($_POST["unit"] ?? "pcs");

    $notes = trim($_POST["notes"] ?? "");


    if (!in_array(
        $type,
        ["plant", "fertilizer", "tool"],
        true
    )) {
        sendResponse(false, "Invalid item type.");
    }


    if ($catalogId <= 0) {
        sendResponse(false, "Please select an item.");
    }


    if ($stock < 0) {
        sendResponse(false, "Stock cannot be negative.");
    }


    if ($unit === "") {
        $unit = "pcs";
    }

    $unit = substr($unit, 0, 30);


    $catalogTable =
        $type === "plant"
            ? "plant_catalog"
            : (
                $type === "fertilizer"
                    ? "fertilizer_catalog"
                    : "tool_catalog"
            );


    $stmt = $conn->prepare(
        "SELECT name FROM $catalogTable WHERE id = ?"
    );

    if (!$stmt) {
        sendResponse(
            false,
            "Catalog query could not be prepared."
        );
    }


    $stmt->bind_param(
        "i",
        $catalogId
    );


    if (!$stmt->execute()) {

        $stmt->close();

        sendResponse(
            false,
            "Catalog query failed."
        );
    }


    $result = $stmt->get_result();

    $catalogItem = $result->fetch_assoc();

    $stmt->close();


    if (!$catalogItem) {
        sendResponse(
            false,
            "Selected item was not found."
        );
    }


    $name = $catalogItem["name"];


    $categoryName =
        $type === "plant"
            ? "Hydroponic Plant"
            : (
                $type === "fertilizer"
                    ? "Fertilizer"
                    : "Tool"
            );


    $stmt = $conn->prepare(
        "
        SELECT category_id
        FROM inventory_categories
        WHERE category_name = ?
        "
    );

    if (!$stmt) {
        sendResponse(
            false,
            "Category query could not be prepared."
        );
    }


    $stmt->bind_param(
        "s",
        $categoryName
    );


    if (!$stmt->execute()) {

        $stmt->close();

        sendResponse(
            false,
            "Category query failed."
        );
    }


    $result = $stmt->get_result();

    $category = $result->fetch_assoc();

    $stmt->close();


    if (!$category) {
        sendResponse(
            false,
            "Inventory category was not found."
        );
    }


    $categoryId = intval($category["category_id"]);


    /* IMAGE */

    $imagePath = null;


    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        if (
            $_FILES["image"]["error"] !== UPLOAD_ERR_OK
        ) {
            sendResponse(
                false,
                "Image upload failed."
            );
        }


        if (
            $_FILES["image"]["size"] >
            5 * 1024 * 1024
        ) {
            sendResponse(
                false,
                "Image must be 5MB or smaller."
            );
        }


        $extension =
            strtolower(
                pathinfo(
                    $_FILES["image"]["name"],
                    PATHINFO_EXTENSION
                )
            );


        $allowed = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];


        if (!in_array(
            $extension,
            $allowed,
            true
        )) {
            sendResponse(
                false,
                "Only JPG, JPEG, PNG, and WEBP images are allowed."
            );
        }


        $uploadDir = "../uploads/inventory/";


        if (
            !is_dir($uploadDir) &&
            !mkdir($uploadDir, 0755, true)
        ) {
            sendResponse(
                false,
                "Upload folder could not be created."
            );
        }


        $fileName =
            uniqid("item_", true)
            . "."
            . $extension;


        $filePath =
            $uploadDir . $fileName;


        if (
            !move_uploaded_file(
                $_FILES["image"]["tmp_name"],
                $filePath
            )
        ) {
            sendResponse(
                false,
                "Could not save uploaded image."
            );
        }


        $imagePath =
            "uploads/inventory/" . $fileName;
    }


    /* INSERT */

    $stmt = $conn->prepare(
        "
        INSERT INTO inventory_items
        (
            catalog_id,
            category_id,
            item_name,
            picture,
            quantity,
            unit,
            notes
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )
        "
    );


    if (!$stmt) {

        if ($imagePath) {
            @unlink("../" . $imagePath);
        }

        sendResponse(
            false,
            "Insert query could not be prepared: "
            . $conn->error
        );
    }


    $stmt->bind_param(
        "iissdss",
        $catalogId,
        $categoryId,
        $name,
        $imagePath,
        $stock,
        $unit,
        $notes
    );


    if (!$stmt->execute()) {

        $error = $stmt->error;

        $stmt->close();

        if ($imagePath) {
            @unlink("../" . $imagePath);
        }

        sendResponse(
            false,
            "Could not add item: " . $error
        );
    }


    $newId = $stmt->insert_id;

    $stmt->close();


    /* INITIAL STOCK */

    if ($stock > 0) {

        $stmt = $conn->prepare(
            "
            INSERT INTO inventory_transactions
            (
                inventory_id,
                transaction_type,
                quantity,
                reason
            )
            VALUES
            (
                ?,
                'IN',
                ?,
                'Initial stock'
            )
            "
        );


        if ($stmt) {

            $stmt->bind_param(
                "id",
                $newId,
                $stock
            );

            $stmt->execute();

            $stmt->close();
        }
    }


    sendResponse(
        true,
        "Item added successfully.",
        [
            "id" => $newId
        ]
    );
}


/* STOCK */

if ($action === "stock") {

    $id = intval($_POST["id"] ?? 0);

    $change = floatval($_POST["change"] ?? 0);


    if (
        $id <= 0 ||
        $change == 0
    ) {
        sendResponse(
            false,
            "Invalid stock update."
        );
    }


    $stmt = $conn->prepare(
        "
        SELECT quantity
        FROM inventory_items
        WHERE inventory_id = ?
        AND is_active = 1
        "
    );


    if (!$stmt) {
        sendResponse(
            false,
            "Stock query could not be prepared."
        );
    }


    $stmt->bind_param(
        "i",
        $id
    );


    if (!$stmt->execute()) {

        $stmt->close();

        sendResponse(
            false,
            "Stock query failed."
        );
    }


    $result = $stmt->get_result();

    $item = $result->fetch_assoc();

    $stmt->close();


    if (!$item) {
        sendResponse(
            false,
            "Inventory item was not found."
        );
    }


    $currentStock =
        floatval($item["quantity"]);


    $newStock =
        $currentStock + $change;


    if ($newStock < 0) {
        sendResponse(
            false,
            "Stock cannot go below 0."
        );
    }


    $stmt = $conn->prepare(
        "
        UPDATE inventory_items
        SET quantity = ?
        WHERE inventory_id = ?
        "
    );


    if (!$stmt) {
        sendResponse(
            false,
            "Stock update could not be prepared."
        );
    }


    $stmt->bind_param(
        "di",
        $newStock,
        $id
    );


    if (!$stmt->execute()) {

        $error = $stmt->error;

        $stmt->close();

        sendResponse(
            false,
            "Stock update failed: " . $error
        );
    }


    $stmt->close();


    $transactionType =
        $change > 0
            ? "IN"
            : "OUT";


    $transactionQuantity =
        abs($change);


    $reason =
        $change > 0
            ? "Stock increased"
            : "Stock decreased";


    $stmt = $conn->prepare(
        "
        INSERT INTO inventory_transactions
        (
            inventory_id,
            transaction_type,
            quantity,
            reason
        )
        VALUES
        (
            ?,
            ?,
            ?,
            ?
        )
        "
    );


    if (!$stmt) {
        sendResponse(
            false,
            "Transaction could not be prepared."
        );
    }


    $stmt->bind_param(
        "isds",
        $id,
        $transactionType,
        $transactionQuantity,
        $reason
    );


    if (!$stmt->execute()) {

        $error = $stmt->error;

        $stmt->close();

        sendResponse(
            false,
            "Transaction record failed: " . $error
        );
    }


    $stmt->close();


    sendResponse(
        true,
        "Stock updated successfully.",
        [
            "stock" => $newStock
        ]
    );
}


/* NOTES */

if ($action === "notes") {

    $id = intval($_POST["id"] ?? 0);

    $notes = trim($_POST["notes"] ?? "");


    if ($id <= 0) {
        sendResponse(
            false,
            "Invalid item."
        );
    }


    $stmt = $conn->prepare(
        "
        UPDATE inventory_items
        SET notes = ?
        WHERE inventory_id = ?
        "
    );


    if (!$stmt) {
        sendResponse(
            false,
            "Notes update could not be prepared."
        );
    }


    $stmt->bind_param(
        "si",
        $notes,
        $id
    );


    if (!$stmt->execute()) {

        $error = $stmt->error;

        $stmt->close();

        sendResponse(
            false,
            "Notes could not be saved: " . $error
        );
    }


    $stmt->close();


    sendResponse(
        true,
        "Notes saved successfully."
    );
}


sendResponse(
    false,
    "Invalid action."
);

?>