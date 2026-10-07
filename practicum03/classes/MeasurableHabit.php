<?php
declare(strict_types=1);

require_once __DIR__ . '/Habit.php';


class MeasurableHabit extends Habit
{
    private string $unit;
    private float $targetValue;

    public function __construct(
        string $name,
        int $targetPerWeek,
        string $unit,
        float $targetValue,
        array $completedDates = []
    ) {
        parent::__construct($name, $targetPerWeek, $completedDates);
        $this->unit = $unit;
        $this->targetValue = $targetValue;
    }

    public function getUnit(): string
    {
        return $this->unit;
    }

    public function getTargetValue(): float
    {
        return $this->targetValue;
    }

    public function getInfo(): string
    {
        return parent::getInfo() . ", ціль за раз: {$this->targetValue} {$this->unit}";
    }
}