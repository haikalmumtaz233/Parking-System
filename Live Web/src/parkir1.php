<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Parkir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body style="background: #D4D4D4;">
    <div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 100vh;">
        <h1 class="mb-4 fw-bold" style="font-family: 'Poppins', sans-serif;">LOG PARKIR</h1>

        <div class="mb-4 d-flex justify-content-evenly w-25">
            <a href="#"><button type="button" class="btn btn-light fw-bold" style="box-shadow: 9px 5px 10px 2px #000; ">Slot 1</button></a>
            <a href="parkir2.php"><button type="button" class="btn btn-warning fw-bold" style="box-shadow: 9px 5px 10px 2px #000">Slot 2</button></a>
            <a href="parkir3.php"><button type="button" class="btn btn-info fw-bold" style="box-shadow: 9px 5px 10px 2px #000">Slot 3</button></a>
        </div>

        <div class="card p-4 overflow-auto" style="width: 40%; box-shadow: 9px 5px 10px 4px #000;">
            <h3 class="text-center mb-3 fw-bold" style="font-family:'Poppins'">Slot 1</h3>
            <div style="max-height: 300px; overflow-y: auto;">
                <table class="table table-striped text-center">
                    <thead class="table-dark" style="position: sticky; top: 0; z-index: 1;">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Masuk</th>
                            <th scope="col">Keluar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'connect.php';
                        $i = 1;

                        // Select rows with status 'masuk' or 'keluar'
                        $sql = "SELECT * FROM log_parkir WHERE status IN ('masuk', 'keluar') AND slot = 1";
                        $query = mysqli_query($connect, $sql);

                        while ($data = mysqli_fetch_array($query)) {
                        ?>
                            <tr>
                                <th scope="row"><?= $i ?></th>
                                <td class="waktu-masuk"><?= ($data['status'] == 'masuk') ? $data['waktu'] : '' ?></td>
                                <td class="waktu-keluar"><?= ($data['status'] == 'keluar') ? $data['waktu'] : '' ?></td>
                            </tr>
                        <?php
                            $i += 1;
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>