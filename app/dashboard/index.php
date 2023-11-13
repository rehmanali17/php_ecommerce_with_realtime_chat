<?php
require '../helpers/authentication.php';
require '../database/dbconfig.php';
$db = new DatabaseConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management</title>
    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap-5.3.0.min.css" rel="stylesheet">
    <!-- Font Awesome for ellipsis icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Product Management</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../helpers/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<ul class="nav nav-fill nav-tabs" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link <?php if ($_SESSION['user']['type'] != 'SELLER') {
            echo 'active';
        } ?>" id="fill-tab-0" data-bs-toggle="tab" href="#fill-tabpanel-0" role="tab"
           aria-controls="fill-tabpanel-0" aria-selected="true"> Seller Products </a>
    </li>
    <?php if ($_SESSION['user']['type'] == 'SELLER') { ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link <?php if ($_SESSION['user']['type'] == 'SELLER') {
                echo 'active';
            } ?>" id="fill-tab-1" data-bs-toggle="tab" href="#fill-tabpanel-1" role="tab"
               aria-controls="fill-tabpanel-1" aria-selected="false"> My products </a>
        </li>
    <?php } ?>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="fill-tab-2" data-bs-toggle="tab" href="#fill-tabpanel-2" role="tab"
           aria-controls="fill-tabpanel-2" aria-selected="false"> Messages </a>
    </li>
</ul>

<div class="container mt-3">
    <?php include '../helpers/session-message.php' ?>
</div>

<div class="tab-content pt-2" id="tab-content">
    <div class="tab-pane <?php if ($_SESSION['user']['type'] != 'SELLER') {
        echo 'active';
    } ?>" id="fill-tabpanel-0" role="tabpanel" aria-labelledby="fill-tab-0">

        <section style="background-color: #eee;">
            <div class="container py-5">
                <div class="row">
                    <?php
                    $sql = "SELECT prod.id AS prodId, prod.name AS prodName, prod.description, prod.price, prod.quantity, prod.file_path, usr.id AS usrId, usr.display_name AS userName 
                                                         FROM products prod
                                                         JOIN users usr ON usr.id = prod.created_by
                                                         WHERE prod.created_by != :created_by ORDER BY prod.created_at DESC;";
                    $created_by = $_SESSION['user']['id'];
                    $queryArgs = [
                        ':created_by' => $created_by
                    ];
                    $statement = $db->executePreparedQuery($sql, $queryArgs);

                    if ($statement->rowCount() > 0) {
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {
                            ?>
                            <div class="col-md-12 col-lg-4 mb-4">
                                <div class="card" style="height: 100%">
                                    <div class="d-flex justify-content-between p-3">
                                        <p class="lead mb-0"><?php echo $row['prodName'] ?></p>
                                        <div class="ellipsis-icon">
                                            <i class="fas fa-ellipsis-v" style="cursor: pointer"
                                               data-bs-toggle="dropdown"
                                               aria-expanded="false"></i>
<!--                                            <ul class="dropdown-menu">-->
<!--                                                <li><a class="dropdown-item" href="#">Update</a></li>-->
<!--                                                <li><a class="dropdown-item" href="#">Delete</a></li>-->
<!--                                            </ul>-->
                                        </div>
                                    </div>
                                    <img src="<?php echo $row['file_path'] ?>"
                                         class="card-img-top" style="object-fit: cover; width: 100%; height: 100%;"
                                         alt="<?php echo $row['prodName'] ?>"/>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="mb-0">Seller</h5>
                                            <h5 class="text-dark mb-0"><?php echo $row['userName'] ?></h5>
                                        </div>
                                        <hr/>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="mb-0">Price</h5>
                                            <h5 class="text-dark mb-0">$<?php echo $row['price'] ?></h5>
                                        </div>
                                        <hr/>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="mb-0">Quantity</h5>
                                            <h5 class="text-dark mb-0"><?php echo $row['quantity'] ?></h5>
                                        </div>
                                        <hr/>
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="mb-0">Description</h5>
                                            <h5 class="text-dark mb-0 text-end"><?php echo $row['description'] ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } else { ?>
                        <p class="alert alert-danger">No product added by other sellers yet</p>
                    <?php } ?>
                </div>
            </div>
        </section>

    </div>

    <?php if ($_SESSION['user']['type'] == 'SELLER') { ?>
        <div class="tab-pane <?php if ($_SESSION['user']['type'] == 'SELLER') {
            echo 'active';
        } ?>" id="fill-tabpanel-1" role="tabpanel" aria-labelledby="fill-tab-1">


            <div class="container mb-3">
                <div class="row">
                    <div class="col-3 ml-auto">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#addProductModal">
                            Add New Product
                        </button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Add product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form>
                                <div class="mb-3 visually-hidden" id="addProductImagePreviewContainer">
                                    <img id="addProductImagePreview" class="image-preview" alt="Image Preview">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="addProductImage" class="form-label">Choose Product Image:</label>
                                    <input type="file" class="form-control" id="addProductImage" name="image"
                                           accept="image/*" onchange="addProductPreviewImage()">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputData" class="form-label">Name:</label>
                                    <input type="text" class="form-control" id="addProductName">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputData" class="form-label">Price:</label>
                                    <input type="text" class="form-control" id="addProductPrice">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputData" class="form-label">Quantity:</label>
                                    <input type="text" class="form-control" id="addProductQuantity">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="message-text" class="col-form-label">Description:</label>
                                    <textarea class="form-control" id="addProductDescription"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="saveProduct()">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" id="updateProductModalBtn" class="visually-hidden" data-bs-toggle="modal"
                    data-bs-target="#updateProductModal">
                Update product
            </button>

            <div class="modal fade" id="updateProductModal" tabindex="-1" aria-labelledby="updateProductModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateProductModalLabel">Update product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form>
                                <input type="hidden" id="updateProductId">
                                <div class="form-group mb-2">
                                    <label for="inputData" class="form-label">Name:</label>
                                    <input type="text" class="form-control" id="updateProductName">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputData" class="form-label">Price:</label>
                                    <input type="text" class="form-control" id="updateProductPrice">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="inputData" class="form-label">Quantity:</label>
                                    <input type="text" class="form-control" id="updateProductQuantity">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="message-text" class="col-form-label">Description:</label>
                                    <textarea class="form-control" id="updateProductDescription"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" onclick="updateProduct()">Save</button>
                        </div>
                    </div>
                </div>
            </div>

            <section style="background-color: #eee;">
                <div class="container py-5">
                    <div class="row">
                        <?php
                        $sql = "SELECT id, name, description, price, quantity, file_path FROM products WHERE created_by = :created_by ORDER BY created_at DESC;";
                        $created_by = $_SESSION['user']['id'];
                        $queryArgs = [
                            ':created_by' => $created_by
                        ];
                        $statement = $db->executePreparedQuery($sql, $queryArgs);

                        if ($statement->rowCount() > 0) {
                            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($rows as $row) {
                                ?>
                                <div class="col-md-12 col-lg-4 mb-4">
                                    <div class="card" style="height: 100%">
                                        <div class="d-flex justify-content-between p-3">
                                            <p class="lead mb-0"><?php echo $row['name'] ?></p>
                                            <div class="ellipsis-icon">
                                                <i class="fas fa-ellipsis-v" style="cursor: pointer"
                                                   data-bs-toggle="dropdown"
                                                   aria-expanded="false"></i>
                                                <ul class="dropdown-menu">
                                                    <li><a style="cursor: pointer" class="dropdown-item"
                                                           onclick="openUpdateProductModal(<?php echo $row['id'] ?>)"
                                                        >Update</a></li>
                                                    <li><a style="cursor: pointer" id="deleteProductBtn"
                                                           class="dropdown-item" data-id="<?php echo $row['id'] ?>">Delete</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <img src="<?php echo $row['file_path'] ?>"
                                             class="card-img-top" style="object-fit: cover; width: 100%; height: 100%;"
                                             alt="<?php echo $row['name'] ?>"/>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5 class="mb-0">Price</h5>
                                                <h5 class="text-dark mb-0">$<?php echo $row['price'] ?></h5>
                                            </div>

                                            <hr/>

                                            <div class="d-flex justify-content-between mb-3">
                                                <h5 class="mb-0">Quantity</h5>
                                                <h5 class="text-dark mb-0"><?php echo $row['quantity'] ?></h5>
                                            </div>

                                            <hr/>

                                            <div class="d-flex justify-content-between mb-3">
                                                <h5 class="mb-0">Description</h5>
                                                <h5 class="text-dark mb-0 text-end"><?php echo $row['description'] ?></h5>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } else { ?>
                            <p class="alert alert-danger">No product added yet</p>
                        <?php } ?>
                    </div>
                </div>
            </section>

        </div>
    <?php } ?>

    <div class="tab-pane" id="fill-tabpanel-2" role="tabpanel" aria-labelledby="fill-tab-2">

        <?php
        $sql = "SELECT * FROM users WHERE id != :id ORDER BY created_at DESC;";
        $id = $_SESSION['user']['id'];
        $queryArgs = [
            ':id' => $id
        ];
        $statement = $db->executePreparedQuery($sql, $queryArgs);
        if ($statement->rowCount() > 0) {
            ?>

            <div class="chat-container clearfix">
                <div class="people-list" id="people-list">
                    <ul class="list">
                        <?php
                        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($rows as $row) {
                            ?>
                            <li class="clearfix">
                                <div class="about">
                                    <div class="name" id="chatHistoryUser" data-id="<?php echo $row['id'] ?>" ><?php echo $row['display_name'] ?></div>
                                    <div class="status">
                                        <i class="fa fa-circle online"></i> online
                                    </div>
                                </div>
                            </li>
                            <hr/>
                        <?php }
                        ?>
                    </ul>
                </div>

                <div class="chat">

                    <div class="chat-header clearfix">
                        <div class="chat-about">
                            <div class="chat-with">Chat with Other People</div>
                        </div>
                    </div>

                    <div class="chat-history">
                        <ul id="chatHistoryContainer">

                        </ul>
                    </div> <!-- end chat-history -->

                    <div class="chat-message clearfix">
                        <textarea name="message-to-send" id="chatMessageInput" placeholder="Type your message"
                                  rows="3"></textarea>

                        <i class="fa fa-file-o"></i> &nbsp;&nbsp;&nbsp;
                        <i class="fa fa-file-image-o"></i>

                        <button id="sendChatMessageBtn">Send</button>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>
</div>

<script src="../assets/js/bootstrap-5.3.0.min.js"></script>
<script src="../assets/js/custom.js"></script>
</body>
</html>

