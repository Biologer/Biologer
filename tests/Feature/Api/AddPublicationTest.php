<?php

namespace Tests\Feature\Api;

use App\Publication;
use App\PublicationType;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AddPublicationTest extends TestCase
{
    /**
     * @test
     */
    public function guest_cannot_add_publication()
    {
        $count = Publication::count();
        $response = $this->postJson('/api/publications', $this->validBookChapter());

        $response->assertUnauthorized();
        Publication::assertCount($count);
    }

    /**
     * @test
     */
    public function unauthorized_user_cannot_add_publication()
    {
        $count = Publication::count();
        Passport::actingAs(factory(User::class)->create());
        $response = $this->postJson('/api/publications', $this->validBookChapter());

        $response->assertForbidden();
        Publication::assertCount($count);
    }

    /**
     * @test
     */
    public function authorized_user_can_add_new_book()
    {
        $this->seed('RolesTableSeeder');
        Passport::actingAs($user = factory(User::class)->create()->assignRoles('admin'));

        $response = $this->postJson('/api/publications', [
            'type' => PublicationType::BOOK,
            'year' => '2019',
            'title' => 'Book title',
            'issue' => '2ed',
            'authors' => [
                ['first_name' => 'Marry', 'last_name' => 'Author'],
            ],
            'editors' => [
                ['first_name' => 'Jane', 'last_name' => 'Editor'],
            ],
            'place' => 'Kragujevac',
            'publisher' => 'University of Kragujevac',
            'page_count' => 300,
            'link' => 'https://example.com',
            'doi' => '1234567',
            'citation' => 'Custom Citation',
        ]);

        $response->assertCreated();
        $publication = Publication::find($response->json('data.id'));
        $this->assertEquals(PublicationType::BOOK, $publication->type);
        $this->assertEquals(2019, $publication->year);
        $this->assertEquals('Book title', $publication->title);
        $this->assertEquals('2ed', $publication->issue);
        $this->assertEquals([['first_name' => 'Marry', 'last_name' => 'Author']], $publication->authors->all());
        $this->assertEquals([['first_name' => 'Jane', 'last_name' => 'Editor']], $publication->editors->all());
        $this->assertEquals('Kragujevac', $publication->place);
        $this->assertEquals('University of Kragujevac', $publication->publisher);
        $this->assertEquals(300, $publication->page_count);
        $this->assertEquals('https://example.com', $publication->link);
        $this->assertEquals('1234567', $publication->doi);
        $this->assertEquals('Custom Citation', $publication->citation);
        $this->assertTrue($publication->createdBy->is($user));
    }

    /**
     * @test
     */
    public function citation_can_be_determined_dinamically_based_on_available_data_if_left_empty()
    {
        $this->handleValidationExceptions();
        $this->seed('RolesTableSeeder');
        Passport::actingAs($user = factory(User::class)->create()->assignRoles('admin'));

        $response = $this->postJson('/api/publications', $this->validBookChapter([
            'citation' => '',
        ]));

        $response->assertCreated();
        $publication = Publication::find($response->json('data.id'));
        $this->assertEquals('Author M. (2019). Title of Paper. In Editor J. (Ed.). Some Book (2ed, 30-140p). Kragujevac: University of Kragujevac. 1234567', $publication->citation);
    }

    protected function validBookChapter($overrides = [])
    {
        return array_merge([
            'type' => PublicationType::BOOK_CHAPTER,
            'name' => 'Some Book',
            'issue' => '2ed',
            'year' => '2019',
            'title' => 'Title of Paper',
            'authors' => [
                ['first_name' => 'Marry', 'last_name' => 'Author'],
            ],
            'editors' => [
                ['first_name' => 'Jane', 'last_name' => 'Editor'],
            ],
            'place' => 'Kragujevac',
            'publisher' => 'University of Kragujevac',
            'page_count' => 300,
            'page_range' => '30-140p',
            'link' => 'https://example.com',
            'doi' => '1234567',
            'citation' => 'Custom Citation',
        ], $overrides);
    }
}
