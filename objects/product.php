<?php
class Product{

	// database connection and table name
	private $conn;
	private $table_name = "products";

	// object properties
	public $id;
	public $name;
	public $price;
	public $description;
	public $category_id;
	public $timestamp;

	public function __construct($db){
		$this->conn = $db;
	}

	// create product
	function create(){

		// to get time-stamp for 'created' field
		$this->getTimestamp();

		//write query
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					name = ?, price = ?, description = ?, category_id = ?, created = ?";

		$stmt = $this->conn->prepare($query);

		// sanitize
	    $this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		$this->timestamp=htmlspecialchars(strip_tags($this->timestamp));

		$stmt->bindParam(1, $this->name);
		$stmt->bindParam(2, $this->price);
		$stmt->bindParam(3, $this->description);
		$stmt->bindParam(4, $this->category_id);
		$stmt->bindParam(5, $this->timestamp);

		if($stmt->execute()){
			return true;
		}else{
			return false;
		}

	}

	// read products
	function readAll($from_record_num, $records_per_page){

		$query = "SELECT
					id, name, description, price, category_id
				FROM
					" . $this->table_name . "
				ORDER BY
					name ASC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind limit clause variables
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	// used for paging products
	public function countAll(){

		$query = "SELECT id FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	// used when filling up the update product form
	function readOne(){

		$query = "SELECT
					name, price, description, category_id
				FROM
					" . $this->table_name . "
				WHERE
					id = ?
				LIMIT
					0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->description = $row['description'];
		$this->category_id = $row['category_id'];
	}

	// update the product
	function update(){

		$query = "UPDATE
					" . $this->table_name . "
				SET
					name = :name,
					price = :price,
					description = :description,
					category_id  = :category_id
				WHERE
					id = :id";

		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		$this->id=htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':price', $this->price);
		$stmt->bindParam(':description', $this->description);
		$stmt->bindParam(':category_id', $this->category_id);
		$stmt->bindParam(':id', $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// delete the product
	function delete(){

		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		$stmt->bindParam(1, $this->id);

		if($result = $stmt->execute()){
			return true;
		}else{
			return false;
		}
	}

	// used for the 'created' field when creating a product
	function getTimestamp(){
		date_default_timezone_set('Asia/Manila');
		$this->timestamp = date('Y-m-d H:i:s');
	}
}
?>
