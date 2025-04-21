<?php

namespace Tests\Feature\Customer\Item;

use App\Models\Item;
use PHPUnit\Framework\Attributes\Test;
use Tests\FeatureTestCase;

class PreviewItemsControllerTest extends FeatureTestCase
{
    #[Test]
    public function itCanPreviewItemsSuccessfully(): void
    {
        $item1 = Item::factory()->create();
        $item2 = Item::factory()->create();

        $response = $this->get(route('customer.item.preview'));

        $response->assertStatus(200);

        $response->assertJson([
            "succeed" => true,
            "message" => __('response.successful_message'),
            "results" => [
                [
                    "slug" => $item1->slug,
                    "name" => $item1->name,
                    "description" => $item1->descriotion,
                ],
                [
                    "slug" => $item2->slug,
                    "name" => $item2->name,
                    "description" => $item2->descriotion,
                ],
            ]
        ]);
    }
}
