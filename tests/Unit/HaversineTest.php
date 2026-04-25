<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Haversine distance formula.
 * We extract the formula here to test it independently.
 */
class HaversineTest extends TestCase
{
    /**
     * Replicates the formula from AttendanceController.
     */
    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2)
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
           * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }

    public function test_same_point_returns_zero(): void
    {
        $dist = $this->haversine(-2.985, 104.732, -2.985, 104.732);
        $this->assertEquals(0.0, $dist);
    }

    public function test_nearby_point_is_within_100m(): void
    {
        // ~11m away
        $dist = $this->haversine(-2.985, 104.732, -2.9851, 104.7321);
        $this->assertLessThanOrEqual(100, $dist);
    }

    public function test_distant_point_exceeds_100m(): void
    {
        // ~556m away
        $dist = $this->haversine(-2.985, 104.732, -2.990, 104.732);
        $this->assertGreaterThan(100, $dist);
    }

    public function test_palembang_to_jakarta_is_correct_magnitude(): void
    {
        // Palembang to Jakarta ~800km
        $dist = $this->haversine(-2.9761, 104.7754, -6.2088, 106.8456);
        $this->assertGreaterThan(700000, $dist);   // > 700 km
        $this->assertLessThan(900000, $dist);      // < 900 km
    }

    public function test_result_is_in_meters(): void
    {
        // 1 degree latitude ≈ 111 km = 111,000 m
        $dist = $this->haversine(0.0, 0.0, 1.0, 0.0);
        $this->assertGreaterThan(110000, $dist);
        $this->assertLessThan(112000, $dist);
    }

    public function test_symmetry(): void
    {
        $a = $this->haversine(-2.985, 104.732, -3.000, 104.750);
        $b = $this->haversine(-3.000, 104.750, -2.985, 104.732);
        $this->assertEquals($a, $b);
    }
}
