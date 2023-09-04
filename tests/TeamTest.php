<?php declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Entity\Team;
use App\Entity\Game;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNull;

final class TeamTest extends TestCase {
    private ?Team $team = null;

    protected function setUp() : void
    {
        $this->team = new Team();
    }

    public function testName() {
        $team = $this->team;
        $this->assertNull($team->getName());
        $team->setName("MaSuperTeam");
        $this->assertEquals("MaSuperTeam", $team->getName());
    }

    public function testAddGame() {
        $this->assertEmpty($this->team->getGames());
        $this->team->addGame(Game::LOL);
        $this->assertNotEmpty($this->team->getGames());
        $this->team->addGame(Game::LOL);
        $this->assertEquals(1, sizeof($this->team->getGames()));
    }

    public function testAddMember() {
        $team = $this->team;
        $this->assertEmpty($team->getMembers());
        $team->addMember(new User());
        $this->assertEquals(1, sizeof($team->getMembers()));
        $team->addMember(new User());
        $team->addMember(new User());
        $this->assertEquals(3, sizeof($team->getMembers()));
    }

    public function testSetCaptain() {
        $team = $this->team;
        $user = new User();
        assertNull($team->getCaptain());
        $team->setCaptain($user);
        assertEquals($user, $team->getCaptain());
    }
}