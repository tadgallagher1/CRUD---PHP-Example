<?php
// for pagination purposes
$page = isset($_GET['page']) ? $_GET['page'] : 1; // page given in URL parameter, default page is one
$records_per_page = 5; // set number of records per page
$from_record_num = ($records_per_page * $page) - $records_per_page; // calculate for the query LIMIT clause

// include database and object files
include_once 'config/database.php';
include_once 'objects/product.php';
include_once 'objects/category.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

$page_title = "Read Products";
include_once "header.php";

// create product button
echo "<div class='right-button-margin'>";
	echo "<a href='create_product.php' class='btn btn-primary pull-right'>";
		echo "<span class='glyphicon glyphicon-plus'></span> Create Product";
	echo "</a>";
echo "</div>";

// query products
$stmt = $product->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// display the products if there are any
if($num>0){

	echo "<table class='table table-hover table-responsive table-bordered'>";
		echo "<tr>";
			echo "<th>Product</th>";
			echo "<th>Price</th>";
			echo "<th>Description</th>";
			echo "<th>Category</th>";
			echo "<th>Actions</th>";
		echo "</tr>";

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

			extract($row);

			echo "<tr>";
				echo "<td>{$name}</td>";
				echo "<td>{$price}</td>";
				echo "<td>{$description}</td>";
				echo "<td>";
					$category->id = $category_id;
					$category->readName();
					echo $category->name;
				echo "</td>";

				echo "<td>";

					// read product button
					echo "<a href='read_one.php?id={$id}' class='btn btn-primary left-margin'>";
						echo "<span class='glyphicon glyphicon-eye-open'></span> Read";
					echo "</a>";

					// edit product button
					echo "<a href='update_product.php?id={$id}' class='btn btn-info left-margin'>";
						echo "<span class='glyphicon glyphicon-edit'></span> Edit";
					echo "</a>";

					// delete product button
					echo "<a delete-id='{$id}' class='btn btn-danger delete-object'>";
						echo "<span class='glyphicon glyphicon-remove'></span> Delete";
					echo "</a>";

				echo "</td>";

			echo "</tr>";

		}

	echo "</table>";

	// paging buttons
	include_once 'paging.php';
}

// tell the user there are no products
else{
	echo "<div>No products found.</div>";
}
?>

<script>
// JavaScript for deleting product
$(document).on('click', '.delete-object', function(){

	var id = $(this).attr('delete-id');
	var q = confirm("Are you sure?");

	if (q == true){

		$.post('delete_product.php', {
			object_id: id
		}, function(data){
			location.reload();
		}).fail(function() {
			alert('Unable to delete.');
		});

	}

	return false;
});
</script>

<?php
include_once "footer.php";
?>
