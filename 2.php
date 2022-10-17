<?php

class User
{
    public int $id = 1;
    public string $birthday = '10-16';
}

interface FilterInterface
{
    public function isOk(User $user): bool;
}

class TodayBirthdayFilter implements FilterInterface
{
    public function isOk(User $user): bool
    {
        return $user->birthday === '10-16'/*date('m-d')*/;
    }
}

class AvgCheckFilter implements FilterInterface
{
    public function isOk(User $user): bool
    {
        // some code ...
        return true;
    }
}

class Auditory
{
    private array $filters = [];
    public function setFilter(FilterInterface $filter): void { $this->filters[] = $filter;}
    public function isOk(User $user): bool
    {
        foreach ($this->filters as $iFilter) {
            /** @var FilterInterface $iFilter */
            if ($iFilter->isOk($user) === false) {
                return false;
            }
        }

        return true;
    }
}

//Акция
class Promotion
{
    private ?Auditory $auditory;
    private string $description;

    public function __construct(Auditory $auditory, string $description) {
        $this->auditory = $auditory;
        $this->description = $description;
    }

    public function isActiveForUser(User $user): bool { return $this->auditory->isOk($user);}// user удовлетворяет условиям фильтров?
    public function showDescription(): void { echo $this->description; }

}

class PromotionRepository
{
    /**
     * Хранение можно сделать в бд, с подключением фильтров через админку, но для примера просто
     * @return Promotion[]
     */
    public static function all(): array
    {
        $auditory = new Auditory();
        $auditory->setFilter(new TodayBirthdayFilter());
        $auditory->setFilter(new AvgCheckFilter());

        $result[] = new Promotion($auditory, 'Акция в честь дня рождения с чем-то средним!');
        $auditory = new Auditory();
        $auditory->setFilter(new TodayBirthdayFilter());

        $result[] = new Promotion($auditory, 'Акция в честь дня рождения!');

        return $result;
    }
}

class Service
{
    /** @return Promotion[] */
    public function getPromotionsByUser(User $user): array
    {
        $result = [];
        foreach (PromotionRepository::all() as $iPromotion) {
            if ($iPromotion->isActiveForUser($user)) {
                $result[] = $iPromotion;
            }
        }
        return $result;
    }
}

function auth(): User
{
    return new User();
}

function main(): void
{
    $user = auth();

    $service = new Service();
    $promotions = $service->getPromotionsByUser($user);

    var_dump($promotions); die;
}

main();