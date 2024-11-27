<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJAX Fetch by ID</title>
</head>

<body>
    <button id="ajax-btn">Fetch Document</button>
    <div id="output">
        <table border="1">
            <thead>
                <tr>
                    <th>Document ID</th>
                    <th>Document Name</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody id="list">
            </tbody>
        </table>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentIndex = 0;
            $('#ajax-btn').on('click', function() {
                $.ajax({
                    url: 'test.php',
                    method: 'POST',
                    data: {
                        index: currentIndex
                    },
                    success: function(response) {
                        // $('#output').html(response); use .html if you want to replace it
                        $('#list').append(response); // use append to incrementally show it
                        currentIndex++; // Move to the next document for the next click
                    },
                    error: function(xhr, status, error) {
                        console.error('Error: Failed to fetch data. Status code:', xhr.status);
                    }
                });
            });
        });
    </script>
</body>

</html>