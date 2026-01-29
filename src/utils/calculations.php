<?php
// src/utils/calculations.php

class InvestmentCalculator {
    public static function calculateDailyProfit($amount, $plan) {
        if (!isset(PLANS[$plan])) return 0;
        return $amount * (PLANS[$plan]['daily'] / 100);
    }

    public static function calculateTotalProfit($amount, $plan, $days) {
        $daily = self::calculateDailyProfit($amount, $plan);
        return $daily * $days;
    }

    public static function calculateReferralBonus($amount) {
        return $amount * (REFERRAL_BONUS / 100);
    }

    public static function calculateWithdrawalFee($amount) {
        return $amount * (WITHDRAWAL_FEE / 100);
    }

    public static function calculateNetWithdrawal($amount) {
        $fee = self::calculateWithdrawalFee($amount);
        return $amount - $fee;
    }

    public static function daysRemaining($startDate, $duration) {
        $endDate = date('Y-m-d', strtotime($startDate . " + $duration days"));
        $remaining = strtotime($endDate) - time();
        return max(0, ceil($remaining / (60 * 60 * 24)));
    }
}
?>