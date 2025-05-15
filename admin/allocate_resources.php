<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Allocate Resources</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        ol {
            font-size: 16px;
            color: #444;
            line-height: 1.8;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #2980b9;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #1f6391;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Resource Allocation Plan</h2>

    <p>
        Resource allocation involves planning, assigning, and managing the resources you have—
        such as people, time, money, and tools—to complete tasks or projects effectively.
    </p>

    <ol>
        <li><strong>Define the scope of the project you’re working on</strong><br>
        Clearly outline what the project aims to achieve, key deliverables, deadlines, and milestones.</li>

        <li><strong>Estimate what project resources will be needed</strong><br>
        Determine what type and how many resources (people, materials, equipment, budget) the project requires.</li>

        <li><strong>Assess your current resource utilization and resource availability</strong><br>
        Evaluate how existing resources are being used and what is available for allocation.</li>

        <li><strong>Create a resource allocation plan</strong><br>
        Assign resources to specific tasks and schedule them based on priority and availability.</li>

        <li><strong>Keep track of your project resources</strong><br>
        Monitor the usage and availability of resources throughout the project to avoid overuse or underuse.</li>

        <li><strong>Use resource allocation reports</strong><br>
        Generate reports to review performance, identify bottlenecks, and make informed decisions.</li>
    </ol>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
