<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0743273565',
                'description' => 'A story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.',
                'category' => 'Fiction',
                'quantity' => 5,
                'available_quantity' => 5,
                'publisher' => 'Scribner',
                'publication_year' => 1925,
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0446310789',
                'description' => 'The story of young Scout Finch and her father Atticus in a racially divided Alabama town.',
                'category' => 'Fiction',
                'quantity' => 3,
                'available_quantity' => 3,
                'publisher' => 'Grand Central Publishing',
                'publication_year' => 1960,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0451524935',
                'description' => 'A dystopian novel about totalitarianism and surveillance society.',
                'category' => 'Fiction',
                'quantity' => 4,
                'available_quantity' => 4,
                'publisher' => 'Signet',
                'publication_year' => 1949,
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '978-0141439518',
                'description' => 'A romantic novel of manners that follows the emotional development of Elizabeth Bennet.',
                'category' => 'Romance',
                'quantity' => 6,
                'available_quantity' => 6,
                'publisher' => 'Penguin Classics',
                'publication_year' => 1813,
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'isbn' => '978-0547928241',
                'description' => 'A fantasy novel about the adventures of Bilbo Baggins, a hobbit who embarks on a quest.',
                'category' => 'Fantasy',
                'quantity' => 7,
                'available_quantity' => 7,
                'publisher' => 'Houghton Mifflin Harcourt',
                'publication_year' => 1937,
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'isbn' => '978-0316769488',
                'description' => 'A novel about teenage alienation and loss of innocence in post-World War II America.',
                'category' => 'Fiction',
                'quantity' => 4,
                'available_quantity' => 4,
                'publisher' => 'Little, Brown and Company',
                'publication_year' => 1951,
            ],
            [
                'title' => 'Lord of the Flies',
                'author' => 'William Golding',
                'isbn' => '978-0399501487',
                'description' => 'A novel about a group of British boys stranded on an uninhabited island.',
                'category' => 'Fiction',
                'quantity' => 5,
                'available_quantity' => 5,
                'publisher' => 'Penguin Books',
                'publication_year' => 1954,
            ],
            [
                'title' => 'Animal Farm',
                'author' => 'George Orwell',
                'isbn' => '978-0451526342',
                'description' => 'An allegorical novella about a group of farm animals who rebel against their human farmer.',
                'category' => 'Fiction',
                'quantity' => 3,
                'available_quantity' => 3,
                'publisher' => 'Signet',
                'publication_year' => 1945,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
} 