<?php
namespace Lauzis\Cco;

use \Exception;

class Cco
{
    public int $green_starting_balance = 3;
    public int $red_starting_balance = 3;

    /**
     * Method calculates balance after players own action, so either player trows in coin (so balance decreases) or id does not
     * @param int $current_balance balance of player that does the action
     * @param string $current_action the action of current player share/steal
     * @return int should be calculated value depending on players own action but could throw exception in cases when
     * tries to share but has no money, or tries to do action that is not allowed
     * @throws Exception exception if action is not supported or action is not possible if the balance goes negative on action
     */
    private function calculateBalanceAfterOnOwnAction(int $current_balance, string $current_action): int
    {
        return match ($current_action) {
            'share' => $current_balance - 1 >= 0 ? $current_balance - 1 : throw new \Exception("Invalid operation '$current_action' for the current balance '$current_balance'"),
            'steal' => $current_balance,
            default => throw new \Exception("Invalid operation '$current_action'")
        };
    }

    /**
     * Method that calculates balance for other/opposite player
     * @param int $opposite_player_balance the current balance of opposite player so current player is sharing then we give 3 coins to other player
     * @param string $current_player_action current player current action
     * @return int return of the updated balance for opposite player
     * @throws Exception exception if action is not supported
     */
    private function calculateBalanceForOtherPlayer(int $opposite_player_balance, string $current_player_action): int
    {
        return match ($current_player_action) {
            'share' => $opposite_player_balance + 3,
            'steal' => $opposite_player_balance,
            default => throw new \Exception("Invalid operation $current_player_action")
        };
    }

    /**
     * @param array $green_player_actions - list of actions of the green/player1
     * @param array $red_player_actions - list of actions of the red/player2
     * @return array - balance accordingly for green and red players
     * @throws Exception will trow exception if the balance gets negative because of action or invalid action is passed
     */
    public function getCoinBalances(array $green_player_actions, array $red_player_actions): array
    {

        $green_player_balance = $this->green_starting_balance;
        $red_player_balance = $this->red_starting_balance;

        //There is not said, that action list will be equals
        //getting max number so could loop through the longest list
        //basically if one list of actions is longer than another  'doing nothing' in this case is basically 'stealing'
        $max_move_count = max(count($green_player_actions), count($red_player_actions));

        for ($i = 0; $i < $max_move_count; $i++) {
            if (isset($green_player_actions[$i])) {
                $green_player_balance = $this->calculateBalanceAfterOnOwnAction($green_player_balance, $green_player_actions[$i]);
                $red_player_balance = $this->calculateBalanceForOtherPlayer($red_player_balance, $green_player_actions[$i]);
            }
            if (isset($red_player_actions[$i])) {
                $red_player_balance = $this->calculateBalanceAfterOnOwnAction($red_player_balance, $red_player_actions[$i]);
                $green_player_balance = $this->calculateBalanceForOtherPlayer($green_player_balance, $red_player_actions[$i]);
            }
        }

        return [$green_player_balance, $red_player_balance];
    }
}

