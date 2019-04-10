<?php 

class user_model extends CI_Model
{
	public function register_user()
	{

		$options = ['cost'=> 12];
		$encripted_pass = password_hash($this->input->post('password'), PASSWORD_BCRYPT, $options);

		$data = array(

		'name'	=> $this->input->post('name'),
		'contact'	=> $this->input->post('contact'),
		'email'	=> $this->input->post('email'),
		'address'	=> $this->input->post('address'),
		'city'	=> $this->input->post('city'),
		'password' => $encripted_pass

		);

		$insert_data = $this->db->insert('users', $data);
		return $insert_data;

	}

	public function login_user($email, $password, $type)
	{
		$this->db->where('email', $email);
		$this->db->where('type', $type);

		$result = $this->db->get('users');

		$db_password = $result->row('password');

		if(password_verify($password, $db_password))
		{
			return $result->row(0)->id;
		}
		else
		{
			return false;
		}
	}

	##...Get all books and filter category wise books
	public function get_books()
	{
		/*=== SQL join and Data filter ===*/
		$this->db->select('*');
		$this->db->from('category');
		$this->db->join('books', 'books.categoryId = category.id');
		if(isset($_GET['ctg']))
		{
			$a = $_GET['ctg'];
			$query = $this->db->where('category.id', $a);
			$this->db->order_by('books.id', 'DESC');
			$this->db->where('books.status', 1);
			$query = $this->db->get();
			return $query->result();
		}
		$this->db->order_by('books.id', 'DESC');
		$this->db->where('books.status', 1);
		$query = $this->db->get();
		return $query->result();
	}

	public function recent_books()
	{
		$this->db->limit(6);
		$this->db->order_by('id', 'DESC');
		$this->db->where('status', 1);
		$query = $this->db->get('books');
		return $query->result();
	}

	public function cse_books()
	{
		$this->db->limit(6);
		$this->db->where('categoryId', '1');
		$this->db->order_by('id', 'DESC');
		$this->db->where('status', 1);
		$query = $this->db->get('books');
		return $query->result();
	}

	public function add_books()
	{
		$data = $this->upload->data();
		$image_path = base_url("uploads/image/".$data['raw_name'].$data['file_ext']);
		
		$data = array(
			'book_name' => $this->input->post('book_name'),
			'description' => $this->input->post('description'),
			'author' => $this->input->post('author'),
			'publisher' => $this->input->post('publisher'),
			'price' => $this->input->post('price'),
			'quantity' => $this->input->post('quantity'),
			'categoryId' => $this->input->post('categoryId'),
			'book_image' => $image_path,
			'userId' => $this->session->userdata('user_data'),
		);

		$insert_book = $this->db->insert('books', $data);
		return $insert_book;
	}


	public function my_books()
	{
		/*=== SQL join ===*/
		$this->db->select('*');
		$this->db->from('category');
		$this->db->join('books', 'books.categoryId = category.id');

		$this->db->order_by('books.id', 'DESC');
		$this->db->where('books.userId', $this->session->userdata('user_data'));
		$query = $this->db->get();
		return $query->result();	
	}

	public function delete_book($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('books');
	}


	public function reviews($id)
	{
		$data = array(
			'review' => $this->input->post('review'),
			'userId' => $this->session->userdata('user_data'),
			'bookId' => $id
		);

		$insert_review = $this->db->insert('reviews', $data);
		return $insert_review;
	}

	public function get_reviews()
	{
		$this->db->select('*');
		$this->db->from('users');
		$this->db->join('reviews', 'reviews.userId = users.id');

		$this->db->where('reviews.bookId', $this->uri->segment(3));
		$this->db->order_by('reviews.id', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function add_orders()
	{
		$shipping = 40;
		$total = $this->cart->total();
		$total_price = $total + $shipping;
		
		foreach ($this->cart->contents() as $items) {
			$a[] = $items['id'];
			$books = implode(', ', $a);

			$q[] = $items['qty'];
			$quantity = implode(', ', $q);

		$data = array(
			'userId'	=> $this->session->userdata('user_data'),
			'name'		=> $this->input->post('name'),
			'address'	=> $this->input->post('address'),
			'city' 		=> $this->input->post('city'),
			'email'		=> $this->input->post('email'),
			'contact' 	=> $this->input->post('contact'),
			'zipcode'	=> $this->input->post('zipcode'),
			'paymentcheck' => $this->input->post('paymentcheck'),
			'total_price' => $total_price,
			'bookId' => $books,
			'quantity' => $quantity

		);
	}

		$insert_order = $this->db->insert('orders', $data);
		return $insert_order;

	}

	public function my_orders()
	{
		$this->db->order_by('orderId', 'DESC');
		$this->db->where('userId', $this->session->userdata('user_data'));
		$query = $this->db->get('orders');
		return $query->result();
	}

	##...Get all E-books and filter category wise E-books
	public function get_ebooks()
	{
		/*=== SQL join and Data filter ===*/
		$this->db->select('*');
		$this->db->from('category');
		$this->db->join('ebooks', 'ebooks.categoryId = category.id');
		if(isset($_GET['ctg']))
		{
			$a = $_GET['ctg'];
			$query = $this->db->where('category.id', $a);
			$this->db->order_by('ebooks.id', 'DESC');
			$query = $this->db->get();
			return $query->result();
		}
		$this->db->order_by('ebooks.id', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}


} 



 ?>