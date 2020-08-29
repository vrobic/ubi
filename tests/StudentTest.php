<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Grade;
use App\Entity\Student;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

/**
 * /students API endpoint tests.
 */
class StudentTest extends ApiTestCase
{
    /* This trait provided by HautelookAliceBundle will take care of
     * refreshing the database content to a known state before each test. */
    use RefreshDatabaseTrait;

    const HEADERS = [
        'Accept' => 'application/ld+json',
        'Content-Type' => 'application/ld+json',
    ];

    /**
     * @return void
     */
    public function testCreate(): void
    {
        // Request
        static::createClient()->request('POST', '/students', [
            'headers' => self::HEADERS,
            'body' => json_encode([
                'firstName' => 'Vincent',
                'lastName' => 'Robic',
                'birthDate' => '1990-06-24',
            ]),
        ]);

        // Asserts
        $this->assertMatchesResourceCollectionJsonSchema(Student::class);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'firstName' => 'Vincent',
            'lastName' => 'Robic',
            'birthDate' => '1990-06-24T00:00:00+00:00',
        ]);
    }

    /**
     * @return void
     */
    public function testSearch(): void
    {
        // Request
        static::createClient()->request('GET', '/students', [
            'headers' => self::HEADERS,
            'query' => [
                'firstName' => 'Barac',
            ],
        ]);

        // Asserts
        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Student::class);
        $this->assertJsonContains([
            'hydra:member' => [
                [
                    'firstName' => 'Barack',
                    'lastName' => 'Obama',
                ],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testStudentsAverageGrade(): void
    {
        // Request
        $client = static::createClient();
        $client->request('GET', '/students/average-grade', [
            'headers' => self::HEADERS,
        ]);

        // Asserts
        $this->assertResponseIsSuccessful();
        $result = $client->getResponse()->getContent();
        $this->assertIsNumeric($result);
        $this->assertGreaterThanOrEqual(Grade::MIN_VALUE, (float) $result);
        $this->assertLessThanOrEqual(Grade::MAX_VALUE, (float) $result);
    }
}
