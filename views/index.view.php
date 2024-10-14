<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/index.css">
</head>
<body>

<table id="table-container">
    <tr>
        <th>Task</th>
        <th>Title</th>
        <th>Description</th>
        <th>Color Code</th>
    </tr>
    <?php foreach ($decoded_data as $data): ?>
        <tr style="background-color: <?php echo $data['colorCode']; ?>;">
            <td>
                <?php echo htmlspecialchars($data['task']); // Use htmlspecialchars to prevent XSS ?>
            </td>
            <td>
                <?php echo htmlspecialchars($data['title']); // Use htmlspecialchars to prevent XSS ?>
            </td>
            <td>
                <?php echo htmlspecialchars($data['description']); // Use htmlspecialchars to prevent XSS ?>
            </td>
            <td>
                <?php echo htmlspecialchars($data['colorCode']); // Use htmlspecialchars to prevent XSS ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>