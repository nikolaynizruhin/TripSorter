<?php

/**
 * TripSorter.
 * Order boarding cards from departure to arrival.
 */
class TripSorter
{
    /**
     * Cards array.
     *
     * @var array 
     */
    private $cards;

    /**
     * Sorted cards array.
     *
     * @var array 
     */
    private $sortedCards;

    /**
     * Departure card.
     *
     * @var array 
     */
    private $firstCard;

    /**
     * Arrival card.
     *
     * @var array 
     */
    private $lastCard;

    /**
     * Create a new trip sorter instance.
     *
     * @param  array $cards
     * @return void
     */
    function __construct($cards)
    {
        $this->cards = $cards;
    }

    /**
     * Get sorted cards.
     *
     * @return array
     */
    public function getSortedCards()
    {
        return $this->sortedCards;
    }

    /**
     * Sort cards.
     *
     * @return array
     */
    public function sort()
    {
        $this->setFirstLastCards();

        $this->removeFirstLastCardsFromCards();

        $this->addToSortedCards($this->firstCard);

        while (true) {
            foreach ($this->cards as $key => $card) {
                if (end($this->sortedCards)['to'] == $card['from']) {
                    $this->addToSortedCards($card);
                    unset($this->cards[$key]);
                }
                if (end($this->sortedCards)['to'] == $this->lastCard['from']) {
                    $this->addToSortedCards($this->lastCard);
                    return $this->sortedCards;
                }
            }
        }
    }

    /**
     * Formatting boarding cards.
     *
     * @return array
     */
    public function formatting()
    {
        $result = [];
        foreach ($this->sortedCards as $key => $card) {
            $seat = $card['seat'] ? ' Seat ' . $card['seat'] : '';
            $baggage = $card['baggage'] ? '. ' . $card['baggage'] . '.' : '';
            $result[] = 'Take ' . $card['type'] . '. From ' . $card['from'] . ' to ' . $card['to'] . '.' . $seat . $baggage;
        }
        return $result;
    }

    /**
     * Add card to sorted cards.
     *
     * @param  array $card
     * @return void
     */
    protected function addToSortedCards($card)
    {
        $this->sortedCards[] = $card;
    }

    /**
     * Remove first and last card from cards list.
     *
     * @return void
     */
    public function removeFirstLastCardsFromCards()
    {
        foreach ($this->cards as $key => $card) {
            if ($this->firstCard == $card || $this->lastCard == $card) {
                unset($this->cards[$key]);
            }
        }
    }

    /**
     * Set first and last cards.
     *
     * @return void
     */
    public function setFirstLastCards()
    {
        foreach ($this->cards as $key => $card) {
            $isBegin = true;
            $isEnd = true;
            array_map(function($current) use ($card, &$isBegin, &$isEnd) {
                if ($card != $current) {
                    if ($card['from'] == $current['to']) {
                        $isBegin = false;
                    }
                    if ($card['to'] == $current['from']) {
                        $isEnd = false;
                    }
                }
            }, $this->cards);
            if ($isBegin) $this->firstCard = $card;
            if ($isEnd) $this->lastCard = $card;
        }
    }
}
