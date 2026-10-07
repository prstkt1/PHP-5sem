<?php
declare(strict_types=1);

require_once __DIR__ . '/Habit.php';

class HabitTracker
{
  
    private array $habits = [];

    public function addHabit(Habit $habit): void
    {
        $this->habits[] = $habit;
    }

    
    public function listAll(): array
    {
        return $this->habits;
    }

    public function findByName(string $name): ?Habit
    {
        foreach ($this->habits as $habit) {
            if ($habit->getName() === $name) {
                return $habit;
            }
        }
        return null;
    }

    public function streakFor(string $name): int
    {
        $habit = $this->findByName($name);
        if ($habit === null) {
            return 0;
        }

        $day = new DateTimeImmutable('today');
        if (!$habit->isDoneOn($day->format('Y-m-d'))) {
            $day = $day->modify('-1 day');
        }

        $streak = 0;
        while ($habit->isDoneOn($day->format('Y-m-d'))) {
            $streak++;
            $day = $day->modify('-1 day');
        }
        return $streak;
    }
}