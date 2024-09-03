<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infraestructura | Aud Activos</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css" rel="stylesheet" />
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <style>
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #f1f1f1;
            padding: 20px 0;
            text-align: center;
            z-index: 99;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .footer small {
            color: #333;
            font-size: 0.875rem;
        }

        .breadcrumb {
            margin-bottom: 0;
            background-color: #dfdfdf;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: '/';
        }

        .card-title {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <nav class="mb-3" aria-label="breadcrumb" style="background-color: #dfdfdf; padding: 5px;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item fw-bold"><a href="?p=gerenciaVisual/Vprincipal">Gerencia Visual</a></li>
            <li class="breadcrumb-item fw-bold"><a href="?p=gerenciaVisual/Vinfra">Infraestructura</a></li>
            <li class="breadcrumb-item fw-bold active" aria-current="page">Auditoría de Activos</li>
        </ol>
    </nav>
    <div class="container-fluid">
        <div class="container-sm mb-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-muted">Detalles de Activos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="getData"></div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <footer class="footer mt-5 py-3 bg-light text-center text-muted">
        <small>© Copyright <?php echo date('Y'); ?>, J-Frem</small>
    </footer>

    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.umd.min.js"></script>
    <script src="/proyectos/AudActivos/index.js"></script>
</body>

</html>