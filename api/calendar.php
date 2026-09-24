<?php

header("Content-Type: application/json");

require_once "../config.php";

$method = $_SERVER["REQUEST_METHOD"];


if ($method === "GET") {

    $sql = "
        SELECT
            task_id,
            title,
            task_date,
            start_time,
            end_time,
            task_type,
            description
        FROM calendar_tasks
        ORDER BY task_date ASC, start_time ASC
    ";

    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode([
            "success" => false,
            "message" => "Failed to load calendar tasks."
        ]);
        exit;
    }

    $tasks = [];

    while ($row = $result->fetch_assoc()) {

        $tasks[] = [
            "id" => (int) $row["task_id"],
            "title" => $row["title"],
            "date" => $row["task_date"],
            "start" => substr($row["start_time"], 0, 5),
            "end" => $row["end_time"]
                ? substr($row["end_time"], 0, 5)
                : "",
            "type" => $row["task_type"],
            "description" => $row["description"] ?? ""
        ];

    }

    echo json_encode([
        "success" => true,
        "tasks" => $tasks
    ]);

    exit;
}


if ($method === "POST") {

    $data = json_decode(
        file_get_contents("php://input"),
        true
    );

    if (!$data) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid request data."
        ]);

        exit;
    }


    $title =
        trim($data["title"] ?? "");

    $date =
        $data["date"] ?? "";

    $start =
        $data["start"] ?? "";

    $end =
        $data["end"] ?? null;

    $type =
        $data["type"] ?? "other";

    $description =
        trim($data["description"] ?? "");


    if (
        $title === "" ||
        $date === "" ||
        $start === ""
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Title, date, and start time are required."
        ]);

        exit;
    }


    $allowedTypes = [
        "plant",
        "irrigation",
        "fertilizer",
        "inspection",
        "maintenance",
        "other"
    ];


    if (!in_array($type, $allowedTypes, true)) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid task type."
        ]);

        exit;
    }


    $stmt = $conn->prepare("
        INSERT INTO calendar_tasks
        (
            title,
            task_date,
            start_time,
            end_time,
            task_type,
            description
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");


    if (!$stmt) {

        echo json_encode([
            "success" => false,
            "message" => "Failed to prepare database query."
        ]);

        exit;
    }


    $stmt->bind_param(
        "ssssss",
        $title,
        $date,
        $start,
        $end,
        $type,
        $description
    );


    if (!$stmt->execute()) {

        echo json_encode([
            "success" => false,
            "message" => "Failed to save calendar task."
        ]);

        $stmt->close();

        exit;
    }


    $taskId =
        $stmt->insert_id;


    $stmt->close();


    echo json_encode([
        "success" => true,
        "message" => "Calendar task added successfully.",
        "task" => [
            "id" => (int) $taskId,
            "title" => $title,
            "date" => $date,
            "start" => $start,
            "end" => $end ?? "",
            "type" => $type,
            "description" => $description
        ]
    ]);

    exit;
}


echo json_encode([
    "success" => false,
    "message" => "Method not allowed."
]);