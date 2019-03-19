<div class="container">
            <div class="section-title sl">
                <h3>Recent Books</h3>
            </div>
            <div class="row con-flex">
                <?php foreach($books as $book):?>
                <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                    <div id="single-book">
                        <div id="book-image">
                            <?php print '<img src = "'.strip_tags($book->book_image).'" alt = "">';?>
                            <div id="addto-cart"><a href=""><i class="fas fa-shopping-cart"></i> Add to cart</a></div>
                        </div>
                        <div class="book-text">
                            <div id="book-name"><?= substr(htmlentities($book->book_name),0,21) ?></div>
                            <div id="author">By <i><?= $book->author ?></i></div>
                            <div id="price"><?= $book->price ?>.TK</div>
                            <div id="book-details">
                                <?php print '<a href = "'.base_url().'users/book_view/'.$book->id.'">View details</a>'; ?>
                            </div>
                        </div>
                    </div>  
                </div>
            <?php endforeach;?>
        </div>

        <div class="view-btn"><a href="<?= base_url()?>users/all_books">View all <i class="fas fa-arrow-circle-right"></i></a></div>
</div>