<?php

namespace Tests\Feature;

use Illuminate\Http\Response;
use Tests\TestCase;

class TransformTest extends TestCase
{
    /**
     * Test if transformation returns a valid output.
     *
     * @dataProvider validInputData
     *
     * @return void
     */
    public function test_api_transform_valid_data($input, $output)
    {
        $response = $this->json('POST', 'api/transform', json_decode($input, true));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(json_decode($output, true));
    }

    /**
     * Test transformation with invalid input.
     *
     * @dataProvider invalidInputData
     *
     * @return void
     */
    public function test_api_transform_invalid_data($input)
    {
        $response = $this->json('POST', 'api/transform', json_decode($input, true));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return array[]
     */
    public function validInputData()
    {
        return [
            [
                '{"0":[{"id":10,"title":"House","level":0,"children":[],"parent_id":null}]}',
                '[{"id":10,"title":"House","level":0,"children":[],"parent_id":null}]',
            ],
            [
                '{"0":[{"id":10,"title":"House","level":0,"children":[],"parent_id":null}],"1":[{"id":12,"title":"Red Roof","level":1,"children":[],"parent_id":10},{"id":18,"title":"Blue Roof","level":1,"children":[],"parent_id":10},{"id":13,"title":"Wall","level":1,"children":[],"parent_id":10}],"2":[{"id":17,"title":"Blue Window","level":2,"children":[],"parent_id":12},{"id":16,"title":"Door","level":2,"children":[],"parent_id":13},{"id":15,"title":"Red Window","level":2,"children":[],"parent_id":12}]}',
                '[{"id":10,"title":"House","level":0,"children":[{"id":12,"title":"Red Roof","level":1,"children":[{"id":17,"title":"Blue Window","level":2,"children":[],"parent_id":12},{"id":15,"title":"Red Window","level":2,"children":[],"parent_id":12}],"parent_id":10},{"id":18,"title":"Blue Roof","level":1,"children":[],"parent_id":10},{"id":13,"title":"Wall","level":1,"children":[{"id":16,"title":"Door","level":2,"children":[],"parent_id":13}],"parent_id":10}],"parent_id":null}]',
            ],
            [
                '{"0":[{"id":10,"title":"House","level":0,"children":[],"parent_id":null}],"1":[{"id":12,"title":"Red Roof","level":1,"children":[],"parent_id":10},{"id":18,"title":"Blue Roof","level":1,"children":[],"parent_id":10},{"id":13,"title":"Wall","level":1,"children":[],"parent_id":10}],"2":[{"id":17,"title":"Blue Window","level":2,"children":[],"parent_id":12},{"id":16,"title":"Door","level":2,"children":[],"parent_id":13},{"id":15,"title":"Red Window","level":2,"children":[],"parent_id":12}],"3":[{"id":5,"title":"Door Window","level":3,"children":[],"parent_id":16}]}',
                '[{"id":10,"title":"House","level":0,"children":[{"id":12,"title":"Red Roof","level":1,"children":[{"id":17,"title":"Blue Window","level":2,"children":[],"parent_id":12},{"id":15,"title":"Red Window","level":2,"children":[],"parent_id":12}],"parent_id":10},{"id":18,"title":"Blue Roof","level":1,"children":[],"parent_id":10},{"id":13,"title":"Wall","level":1,"children":[{"id":16,"title":"Door","level":2,"children":[{"id":5,"title":"Door Window","level":3,"children":[],"parent_id":16}],"parent_id":13}],"parent_id":10}],"parent_id":null}]',
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidInputData()
    {
        return [
            ['["invalid"]'],
            ['{"0":"invalid"}'],
            ['{"0":[{"id":10,"title":"House","level":0,"children":[],"parent_id":null}],"1":[{"id":12,"title":"Red Roof","level":1,"children":[],"parent_id":1}]}',],
        ];
    }
}
