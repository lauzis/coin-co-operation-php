# Coin Co-Operation


## Task 
https://edabit.com/challenge/F7DBaK85jKLDMiugA

## Task description
Let's say that there exists a machine that gives out free coins, but with a twist!

Separating two people is a wall, and this machine is placed in such a way that both people are able to access it. Spending a coin in this machine will give the person on the other side 3 coins and vice versa.

If both people continually spend coins for each other (SHARING), then they'll both gain a net profit of 2 coins per turn. However, there is always the possibility for someone to act selfishly (STEALING): they spend no coins, yet they still receive the generous 3 coin gift from the other person!

## Solution
Solution code src/Cco.php 
```
<?php
use Lauzis\Cco\Cco;

$cco = new Cco();
$result = $cco->getCoinBalances(['steal','share', 'steal'], ['steal','share', 'steal']);
print_r($result);

```

 

## Tests
Test code tests/CcoClassTest.php

## Prerequisites
- php (8.x)
- composer

## Check if you have everything that is needed
type in commandline, there should be output of the versions
> php -v

> composer --version


## Setup & run tests
1. clone repo
> git clone git@github.com:lauzis/coin-co-operation-php.git
2. cd in project dir
> cd coin-co-operation-php
3. install packages 
> composer install
4. run tests
> ./vendor/bin/phpunit
