<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "db.php";

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
        sendResponse(
            false,
            "Plant catalog query failed: " . $conn->error
        );
    }

    while ($row = $result->fetch_assoc()) {
        $catalog["plant"][] = $row;
    }

    $result = $conn->query(
        "SELECT * FROM fertilizer_catalog ORDER BY name ASC"
    );

    if (!$result) {
        sendResponse(
            false,
            "Fertilizer catalog query failed: " . $conn->error
        );
    }

    while ($row = $result->fetch_assoc()) {
        $catalog["fertilizer"][] = $row;
    }

    $result = $conn->query(
        "SELECT * FROM tool_catalog ORDER BY name ASC"
    );

    if (!$result) {
        sendResponse(
            false,
            "Tool catalog query failed: " . $conn->error
        );
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

if ($action === "get") {

    $result = $conn->query(
        "
        SELECT *
        FROM inventory
        ORDER BY created_at DESC
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

if ($action === "add") {

    $type =
        trim($_POST["item_type"] ?? "");

    $catalogId =
        intval($_POST["catalog_id"] ?? 0);

    $stock =
        intval($_POST["stock"] ?? 0);

    $unit =
        trim($_POST["unit"] ?? "pcs");

    $notes =
        trim($_POST["notes"] ?? "");

    if (!in_array(
        $type,
        ["plant", "fertilizer", "tool"],
        true
    )) {

        sendResponse(
            false,
            "Invalid item type."
        );
    }

    if ($catalogId <= 0) {

        sendResponse(
            false,
            "Please select an item."
        );
    }

    if ($stock < 0) {

        sendResponse(
            false,
            "Stock cannot be negative."
        );
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

    $result =
        $stmt->get_result();

    $catalogItem =
        $result->fetch_assoc();

    $stmt->close();

    if (!$catalogItem) {

        sendResponse(
            false,
            "Selected item was not found."
        );
    }

    $name =
        $catalogItem["name"];

    $imagePath = null;

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE
    ) {

        if (
            $_FILES["image"]["error"] !==
            UPLOAD_ERR_OK
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

        $uploadDir =
            "../uploads/inventory/";

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
            uniqid(
                "item_",
                true
            ) . "." . $extension;

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

    $stmt = $conn->prepare(
        "
        INSERT INTO inventory
        (
            item_type,
            catalog_id,
            name,
            image_path,
            stock,
            unit,
            date_added,
            last_added,
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
            NOW(),
            NOW(),
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
            "Insert query could not be prepared."
        );
    }

    $stmt->bind_param(
        "sississ",
        $type,
        $catalogId,
        $name,
        $imagePath,
        $stock,
        $unit,
        $notes
    );

    if (!$stmt->execute()) {

        $error =
            $stmt->error;

        $stmt->close();

        if ($imagePath) {
            @unlink("../" . $imagePath);
        }

        sendResponse(
            false,
            "Could not add item: " . $error
        );
    }

    $newId =
        $stmt->insert_id;

    $stmt->close();

    sendResponse(
        true,
        "Item added successfully.",
        [
            "id" => $newId
        ]
    );
}

if ($action === "stock") {

    $id =
        intval($_POST["id"] ?? 0);

    $change =
        intval($_POST["change"] ?? 0);

    if (
        $id <= 0 ||
        $change === 0
    ) {

        sendResponse(
            false,
            "Invalid stock update."
        );
    }

    if ($change > 0) {

        $stmt = $conn->prepare(
            "
            UPDATE inventory
            SET
                stock = stock + ?,
                last_added = NOW()
            WHERE id = ?
            "
        );

    } else {

        $stmt = $conn->prepare(
            "
            UPDATE inventory
            SET
                stock = GREATEST(stock + ?, 0)
            WHERE id = ?
            "
        );
    }

    if (!$stmt) {

        sendResponse(
            false,
            "Stock update could not be prepared."
        );
    }

    $stmt->bind_param(
        "ii",
        $change,
        $id
    );

    if (!$stmt->execute()) {

        $error =
            $stmt->error;

        $stmt->close();

        sendResponse(
            false,
            "Stock update failed: " . $error
        );
    }

    if ($stmt->affected_rows === 0) {

        $stmt->close();

        sendResponse(
            false,
            "Inventory item was not found."
        );
    }

    $stmt->close();

    sendResponse(
        true,
        "Stock updated successfully."
    );
}

if ($action === "notes") {

    $id =
        intval($_POST["id"] ?? 0);

    $notes =
        trim($_POST["notes"] ?? "");

    if ($id <= 0) {

        sendResponse(
            false,
            "Invalid item."
        );
    }

    $stmt = $conn->prepare(
        "
        UPDATE inventory
        SET notes = ?
        WHERE id = ?
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

        $error =
            $stmt->error;

        $stmt->close();

        sendResponse(
            false,
            "Notes could not be saved: " . $error
        );
    }

    if ($stmt->affected_rows === 0) {

        $stmt->close();

        sendResponse(
            false,
            "Inventory item was not found."
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