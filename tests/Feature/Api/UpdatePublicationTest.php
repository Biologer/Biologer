<?php

namespace Tests\Feature\Api;

use PHPUnit\Framework\Attributes\Test;
use App\Publication;
use App\PublicationType;
use App\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

final class UpdatePublicationTest extends TestCase
{
    #[Test]
    public function guest_cannot_update_publication(): void
    {
        $publication = Publication::factory()->create();
        $response = $this->putJson("/api/publications/{$publication->id}", $this->validPaper());

        $response->assertUnauthorized();
    }

    #[Test]
    public function unauthorized_user_cannot_update_publication(): void
    {
        $publication = Publication::factory()->create();
        Passport::actingAs(User::factory()->create());
        $response = $this->putJson("/api/publications/{$publication->id}", $this->validPaper());

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_update_publication(): void
    {
        $publication = Publication::factory()->create();
        $this->seed('RolesTableSeeder');
        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/publications/{$publication->id}", $this->validPaper());

        $response->assertSuccessful();
        $publication->refresh();
        $this->assertEquals(PublicationType::PAPER, $publication->type);
        $this->assertEquals(2019, $publication->year);
        $this->assertEquals('Some Journal', $publication->name);
        $this->assertEquals('Title of Paper', $publication->title);
        $this->assertEquals('2019-2', $publication->issue);
        $this->assertEquals([['first_name' => 'Marry', 'last_name' => 'Author']], $publication->authors->all());
        $this->assertEquals([['first_name' => 'Jane', 'last_name' => 'Editor']], $publication->editors->all());
        $this->assertEquals('Kragujevac', $publication->place);
        $this->assertEquals('University of Kragujevac', $publication->publisher);
        $this->assertEquals(110, $publication->page_count);
        $this->assertEquals('https://example.com', $publication->link);
        $this->assertEquals('1234567', $publication->doi);
        $this->assertEquals('Custom Citation', $publication->citation);
    }

    #[Test]
    public function citation_can_be_determined_dinamically_based_on_available_data_if_left_empty(): void
    {
        $publication = Publication::factory()->create();
        $this->seed('RolesTableSeeder');
        Passport::actingAs(User::factory()->create()->assignRoles('admin'));

        $response = $this->putJson("/api/publications/{$publication->id}", $this->validPaper([
            'citation' => '',
        ]));

        $response->assertSuccessful();
        $this->assertEquals('Author M. (2019). Title of Paper. Some Journal, 2019-2, 30-140p. Kragujevac: University of Kragujevac. 1234567', $publication->fresh()->citation);
    }

    protected function validPaper($overrides = [])
    {
        return array_merge([
            'type' => PublicationType::PAPER,
            'name' => 'Some Journal',
            'issue' => '2019-2',
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
            'page_count' => 110,
            'page_range' => '30-140p',
            'link' => 'https://example.com',
            'doi' => '1234567',
            'citation' => 'Custom Citation',
        ], $overrides);
    }
}
