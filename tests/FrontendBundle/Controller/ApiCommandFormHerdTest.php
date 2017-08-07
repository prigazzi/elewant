<?php

declare(strict_types=1);

namespace Tests\Elewant\FrontendBundle\Controller;

use Elewant\Herding\Model\Events\HerdWasFormed;
use Elewant\Herding\Model\ShepherdId;

class ApiCommandFormHerdTest extends ApiCommandBase
{
    /** @var ShepherdId */
    private $shepherdId;

    public function setUp()
    {
        parent::setUp();
        $this->shepherdId = ShepherdId::generate();
        $this->client     = $this->formHerd($this->shepherdId, 'My herd name');
    }

    public function test_command_form_herd_returns_http_status_202()
    {
        $this->assertEquals(202, $this->client->getResponse()->getStatusCode());
    }

    public function test_command_form_herd_emits_HerdWasFormed_event()
    {
        $this->assertCount(1, $this->recordedEvents);

        /** @var HerdWasFormed $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[0];
        $this->assertInstanceOf(HerdWasFormed::class, $eventUnderTest);
        $this->assertSame('My herd name', $eventUnderTest->name());
    }

    public function test_command_form_herd_created_a_correct_herd_projection()
    {
        /** @var HerdWasFormed $eventUnderTest */
        $eventUnderTest = $this->recordedEvents[0];

        $expectedHerdProjection  = [
            'herd_id'     => $eventUnderTest->herdId()->toString(),
            'shepherd_id' => $eventUnderTest->shepherdId()->toString(),
            'name'        => $eventUnderTest->name(),
            'formed_on'   => $eventUnderTest->createdAt()->format('Y-m-d H:i:s'),
        ];
        $projectedHerd = $this->retrieveHerdFromListing($eventUnderTest->herdId()->toString());
        $this->assertSame($expectedHerdProjection, $projectedHerd);
    }
}
