<?php
// set page headers
$page_title = "Create Product";
include_once "header.php";

// read products button
echo "<div class='right-button-margin'>";
	echo "<a href='index.php' class='btn btn-primary pull-right'>";
		echo "<span class='glyphicon glyphicon-list'></span> Read Products";
	echo "</a>";
echo "</div>";

// get database connection
include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();
	
// if the form was submitted
if($_POST){
	
	// instantiate product object
	include_once 'objects/product.php';
	$product = new Product($db);

	// set product property values
	$product->name = $_POST['name'];
	$product->price = $_POST['price'];
	$product->description = $_POST['description'];
	$product->category_id = $_POST['category_id'];
	
	// create the product
	if($product->create()){
		echo "<div class=\"alert alert-success alert-dismissable\">";
			echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
			echo "Product was created.";
		echo "</div>";
	}
	
	// if unable to create the product, tell the user
	else{
		echo "<div class=\"alert alert-danger alert-dismissable\">";
			echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
			echo "Unable to create product.";
		echo "</div>";
	}
}
?>
	 
<!-- HTML form for creating a product -->
<form action='create_product.php' method='post'>
 
	<table class='table table-hover table-responsive table-bordered'>
	 
		<tr>
			<td>Name</td>
			<td><input type='text' name='name' class='form-control' required></td>
		</tr>
		 
		<tr>
			<td>Price</td>
			<td><input type='number' name='price' class='form-control' required></td>
		</tr>
		 
		<tr>
			<td>Description</td>
			<td><textarea name='description' class='form-control'></textarea></td>
		</tr>
		 
		<tr>
			<td>Category</td>
			<td>
			<?php
			// read the product categories from the database
			include_once 'objects/category.php';

			$category = new Category($db);
			$stmt = $category->read();
			
			// put them in a select drop-down
			echo "<select class='form-control' name='category_id'>";
				echo "<option>Select category...</option>";
				
				while ($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
					extract($row_category);
					echo "<option value='{$id}'>{$name}</option>";
				}
				
			echo "</select>";
			?>
			</td>
		</tr>
		 
		<tr>
			<td></td>
			<td>
				<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-plus"></span> Create
				</button>
			</td>
		</tr>
		 
	</table>
</form>
             
<?php
include_once "footer.php";
?>