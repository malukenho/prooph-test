<?php

declare(strict_types=1);

namespace ProophTest;

use PHPUnit_Framework_TestCase;
use Rhumsaa\Uuid\Uuid;

/**
 * @author Jefersson Nathan <malukenho@phpse.net>
 */
abstract class BaseCommandTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider exampleReconstructedCommandsProvider
     *
     * @param Command $command
     */
    public function testToAndFromArrayProducesEquivalentObject($command)
    {
        self::assertEquals(
            $command,
            $command::fromArray($command->toArray())
        );
    }

    /**
     * @dataProvider exampleReconstructedCommandsProvider
     *
     * @param Command $command
     */
    public function testCommandPayloadIsJsonSerializable($command)
    {
        self::assertEquals(
            $command->payload(),
            json_decode(json_encode($command->payload()), true)
        );
    }

    /**
     * @dataProvider exampleReconstructedCommandsProvider
     *
     * @param Command $command
     */
    public function testDomainEventAlwaysHasAnIdentifier($command)
    {
        self::assertInstanceOf(Uuid::class, $command->uuid());
    }

    /**
     * @dataProvider exampleReconstructedCommandsProvider
     *
     * @param Command $command
     */
    public function testDomainEventIsAnEvent($command)
    {
        self::assertSame(Command::TYPE_COMMAND, $command->messageType());
    }

    /**
     * @dataProvider exampleReconstructedCommandsProvider
     *
     * @param Command $command
     */
    public function testDomainAlwaysHasACreatedAt($command)
    {
        self::assertInstanceOf(\DateTimeImmutable::class, $command->createdAt());
    }

    /**
     * @dataProvider exampleReconstructedCommandsProvider
     *
     * @param Command $command
     */
    public function testDomainEventNamesAreNotTranslated($command)
    {
        self::assertSame(get_class($command), $command->messageName());
    }

    /**
     *
     * @dataProvider exampleReconstructedCommandsProvider
     *
     * @param Command $command
     */
    public function testDomainEventAlwaysHasAnAssignedVersion($command)
    {
        self::assertInternalType('int', $command->version());
    }

    /**
     * @return Command[]
     */
    abstract public function exampleReconstructedCommandsProvider(): array;
}
