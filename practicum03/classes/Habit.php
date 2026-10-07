<?php
declare(strict_types=1);

class Habit
{
    private string $name;
    protected int $targetPerWeek;
    private array $completedDates;

    public function __construct(string $name, int $targetPerWeek, array $completedDates = [])
    {
        $this->name = $name;
        $this->targetPerWeek = $targetPerWeek;
        $this->completedDates = array_values(array_unique($completedDates));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTargetPerWeek(): int
    {
        return $this->targetPerWeek;
    }

    public function getCompletedDates(): array
    {
        return $this->completedDates;
    }

    public function markDone(string $date): void
    {
        if (!in_array($date, $this->completedDates, true)) {
            $this->completedDates[] = $date;
        }
    }

    public function isDoneOn(string $date): bool
    {
        return in_array($date, $this->completedDates, true);
    }

    public function countLastDays(int $days = 7): int
    {
        $from = new DateTimeImmutable('today -' . ($days - 1) . ' days');
        $count = 0;
        foreach ($this->completedDates as $date) {
            if (new DateTimeImmutable($date) >= $from) {
                $count++;
            }
        }
        return $count;
    }

    public function getInfo(): string
    {
        return "Звичка «{$this->name}», ціль: {$this->targetPerWeek} р./тиждень";
    }
}