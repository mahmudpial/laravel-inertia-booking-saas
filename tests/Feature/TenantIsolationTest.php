<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Scopes\TenantScope;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected $tenantOneAdmin;
    protected $tenantTwoAdmin;
    protected $superAdmin;
    protected $bookingTenantOne;
    protected $serviceId;
    protected $tenantOneBusinessId;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a valid tenant graph that matches the real schema.
        $this->tenantOneAdmin = User::factory()->create(['role' => 'owner']);
        $this->tenantTwoAdmin = User::factory()->create(['role' => 'owner']);
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);

        $tenantOneBusiness = Business::create([
            'user_id' => $this->tenantOneAdmin->id,
            'name' => 'Tenant One',
            'slug' => 'tenant-one',
        ]);

        $tenantTwoBusiness = Business::create([
            'user_id' => $this->tenantTwoAdmin->id,
            'name' => 'Tenant Two',
            'slug' => 'tenant-two',
        ]);

        $this->tenantOneAdmin->update(['business_id' => $tenantOneBusiness->id]);
        $this->tenantTwoAdmin->update(['business_id' => $tenantTwoBusiness->id]);
        $this->tenantOneBusinessId = $tenantOneBusiness->id;

        $service = Service::create([
            'business_id' => $tenantOneBusiness->id,
            'name' => 'Test Service',
            'description' => null,
            'duration_minutes' => 60,
            'price' => 100.00,
            'is_active' => true,
        ]);

        $this->serviceId = $service->id;

        $this->bookingTenantOne = Booking::create([
            'business_id' => $tenantOneBusiness->id,
            'user_id' => $this->tenantOneAdmin->id,
            'service_id' => $this->serviceId,
            'booking_date' => now()->format('Y-m-d'),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'status' => 'pending',
            'notes' => 'Tenant 1 Deep Isolation Test'
        ]);
    }

    public function test_tenant_cannot_access_or_view_other_tenants_data()
    {
        $this->actingAs($this->tenantTwoAdmin);

        $bookings = Booking::all();

        $this->assertTrue($bookings->isEmpty());
        $this->assertFalse($bookings->contains($this->bookingTenantOne));
    }

    public function test_tenant_resource_automatically_binds_business_id_on_creation()
    {
        $this->actingAs($this->tenantOneAdmin);

        $newBooking = Booking::create([
            'user_id' => $this->tenantOneAdmin->id,
            'service_id' => $this->serviceId,
            'booking_date' => now()->format('Y-m-d'),
            'start_time' => '12:00:00',
            'end_time' => '13:00:00',
            'status' => 'pending',
            'notes' => 'Global Auto-binding Hook Test'
        ]);

        $this->assertEquals(1, $newBooking->business_id);
    }

    public function test_super_admin_can_bypass_tenant_scope_restriction()
    {
        $this->actingAs($this->superAdmin);

        $allBookings = Booking::withoutGlobalScope(TenantScope::class)->get();

        $this->assertNotEmpty($allBookings);
        $this->assertTrue($allBookings->contains($this->bookingTenantOne));
    }

    public function test_user_without_business_id_gets_zero_results_not_unfiltered_results()
    {
        $orphanOwner = User::factory()->create([
            'role' => 'owner',
            'business_id' => null,
        ]);

        $this->actingAs($orphanOwner);

        $bookings = Booking::all();

        $this->assertCount(0, $bookings);
        $this->assertTrue($bookings->isEmpty());
    }

    /** @test */
    public function test_authenticated_customer_can_create_booking_successfully()
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'business_id' => null,
        ]);

        $this->actingAs($customer);

        $booking = Booking::create([
            'user_id' => $customer->id,
            'service_id' => $this->serviceId,
            'business_id' => $this->tenantOneBusinessId,
            'booking_date' => now()->format('Y-m-d'),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'status' => 'pending',
        ]);

        $this->assertNotNull($booking);
        $this->assertEquals($customer->id, $booking->user_id);
        $this->assertEquals($this->tenantOneBusinessId, $booking->business_id);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'user_id' => $customer->id,
            'business_id' => $this->tenantOneBusinessId,
        ]);
    }
}
