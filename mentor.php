<!DOCTYPE html>
<html>
<head>
    <title>Mentor Module - Work Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }
        th, td {
            padding: 12px;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        input {
            width: 50px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mentor - Work Duration</h2>
        <table>
            <tr>
                <th>Event Name</th>
                <th>Time (in Hours)</th>
            </tr>
            <tr>
                <td>Library Cleanup</td>
                <td><input type="number" value="1" min="1"></td>
            </tr>
            <tr>
                <td>Computer Lab Maintenance</td>
                <td><input type="number" value="1" min="1"></td>
            </tr>
            <tr>
                <td>Garden Watering</td>
                <td><input type="number" value="1" min="1"></td>
            </tr>
        </table>
    </div>
</body>
</html>
