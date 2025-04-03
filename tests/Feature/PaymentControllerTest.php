<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function checkout_creates_stripe_session()
    {
        // Подготовка данных
        $response = $this->postJson('/checkout', [
            'total' => 100, // Примерная сумма
        ]);

        // Проверка, что ответ содержит id сессии Stripe
        $response->assertStatus(200)
            ->assertJsonStructure(['id']);
    }

    /** @test */
    public function checkout_returns_error_if_total_is_missing()
    {
        // Проверка на отсутствие 'total' в запросе
        $response = $this->postJson('/checkout', []);

        $response->assertStatus(422) // Ожидаем ошибку валидации
        ->assertJsonValidationErrors('total');
    }

    /** @test */
    public function success_page_is_displayed()
    {
        // Проверка маршрута для страницы успешной оплаты
        $response = $this->get('/payment/success');
        $response->assertStatus(200)
            ->assertViewIs('SuccessPage');
    }

    /** @test */
    public function cancel_page_is_displayed()
    {
        // Проверка маршрута для страницы отмены оплаты
        $response = $this->get('/payment/cancel');
        $response->assertStatus(200)
            ->assertViewIs('CancelPage');
    }
}
