<?php

namespace Tests;

trait TestAssertionHelpers {

    protected function assertArrayItemsHaveKeys($array, $keys)
    {
        foreach ($array as $item) {
            foreach ($keys as $key) {
                $this->assertArrayHasKey($key, $item);
            }
        }
    }

    protected function assertIsJSON($content)
    {
        json_decode($content);
        $this->assertEquals(JSON_ERROR_NONE, json_last_error(), 'Failed asserting that content is json.');
        $this->assertTrue(in_array(substr($content, 0, 1), ['[', '{']), 'Failed asserting that content is json.');
    }

    protected function seeJsonStructure(array $structure = null, $response)
    {
        $responseData = $response->decodeResponseJson();

        foreach ($structure as $key => $value) {
            if (is_array($value) && str_contains($key, '*') && $key !== '*') {
                $this->assertInternalType('array', $responseData);


                $innerResponseData = $responseData;
                foreach(explode('.', $key) as $innerKeyPiece) {
                    if ($innerKeyPiece === '*') {
                        break;
                    }

                    $innerResponseData = $innerResponseData[$innerKeyPiece];
                }

                foreach ($innerResponseData as $responseDataItem) {
                    foreach ($structure[$key] as $keyToCheck) {
                        $this->assertArrayHasKey($keyToCheck, $responseDataItem);
                    }
                }
            } else if (is_array($value) && $key === '*') {
                $this->assertInternalType('array', $responseData);

                foreach ($responseData as $responseDataItem) {
                    $response->assertJson($structure['*'], $responseDataItem);
                }
            } elseif (is_array($value)) {
                $this->assertArrayHasKey($key, $responseData);
                $response->assertJson($structure[$key], $responseData[$key]);
            } else {
                $this->assertArrayHasKey($value, $responseData);
            }
        }

        return $this;
    }

    protected function assertValidationError($field, $response)
    {
        $response->assertStatus(422);
        $this->assertArrayHasKey($field, $response->decodeResponseJson());
    }

}