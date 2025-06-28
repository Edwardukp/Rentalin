# Carbon Date Operations - Best Practices

This document outlines best practices for working with Carbon date operations in our Laravel application to prevent type errors and ensure consistent behavior.

## Common Issues and Solutions

### 1. Type Safety with Carbon Methods

**Problem**: Carbon methods like `addMonths()`, `addDays()`, etc., expect `int|float` parameters but may receive string values from form inputs or database queries.

**Solution**: Always cast values to appropriate types before passing to Carbon methods.

```php
// ❌ Bad - May cause type errors
$endDate = $startDate->addMonths($booking->durasi);

// ✅ Good - Explicit type casting
$endDate = $startDate->copy()->addMonths((int) $booking->durasi);
```

### 2. Immutability and Object Mutation

**Problem**: Carbon methods like `addMonths()` mutate the original Carbon instance, which can cause unexpected behavior when the same date object is used multiple times.

**Solution**: Always use `copy()` before performing date arithmetic to avoid mutating the original instance.

```php
// ❌ Bad - Mutates original date
public function getEndDateAttribute()
{
    return $this->tanggal_mulai->addMonths($this->durasi);
}

// ✅ Good - Uses copy to preserve original
public function getEndDateAttribute()
{
    return $this->tanggal_mulai->copy()->addMonths((int) $this->durasi);
}
```

### 3. Model Attribute Casting and Mutators

**Best Practice**: Use Eloquent casts and mutators to ensure data types are consistent.

```php
// In your model
protected function casts(): array
{
    return [
        'tanggal_mulai' => 'date',
        'durasi' => 'integer',
    ];
}

// Add mutator for extra type safety
public function setDurasiAttribute($value)
{
    $this->attributes['durasi'] = (int) $value;
}
```

### 4. Controller Validation and Type Safety

**Best Practice**: Validate and cast input data in controllers before using with Carbon.

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'durasi' => 'required|integer|min:1|max:24',
        'tanggal_mulai' => 'required|date|after_or_equal:today',
    ]);

    // Explicit casting for extra safety
    $durasi = (int) $validated['durasi'];
    $startDate = Carbon::parse($validated['tanggal_mulai']);
    $endDate = $startDate->copy()->addMonths($durasi);
}
```

### 5. Blade Template Best Practices

**Best Practice**: Use `copy()` and explicit casting in Blade templates when performing date arithmetic.

```blade
{{-- ❌ Bad - May mutate original and cause type errors --}}
{{ $booking->tanggal_mulai->addMonths($booking->durasi)->format('M d, Y') }}

{{-- ✅ Good - Safe and type-aware --}}
{{ $booking->tanggal_mulai->copy()->addMonths((int) $booking->durasi)->format('M d, Y') }}
```

## Testing Carbon Operations

Always write tests to verify Carbon operations work correctly:

```php
/** @test */
public function it_calculates_end_date_without_mutating_original_date()
{
    $booking = Booking::factory()->create(['durasi' => '3']);
    $originalDate = $booking->tanggal_mulai->copy();
    
    // Call accessor multiple times
    $endDate1 = $booking->end_date;
    $endDate2 = $booking->end_date;
    
    // Original should not be mutated
    $this->assertEquals($originalDate, $booking->tanggal_mulai);
    $this->assertEquals($endDate1, $endDate2);
}
```

## Quick Checklist

Before using Carbon date operations, ensure:

- [ ] Input values are properly validated and cast to correct types
- [ ] Use `copy()` before any date arithmetic to avoid mutation
- [ ] Cast string values to integers: `(int) $value`
- [ ] Model attributes have proper casts defined
- [ ] Add mutators for critical fields that need type safety
- [ ] Write tests to verify behavior
- [ ] Use consistent patterns across controllers and views

## Common Carbon Methods and Type Safety

| Method | Parameter Type | Safe Usage |
|--------|---------------|------------|
| `addMonths()` | `int\|float` | `$date->copy()->addMonths((int) $months)` |
| `addDays()` | `int\|float` | `$date->copy()->addDays((int) $days)` |
| `addHours()` | `int\|float` | `$date->copy()->addHours((int) $hours)` |
| `subMonths()` | `int\|float` | `$date->copy()->subMonths((int) $months)` |
| `subDays()` | `int\|float` | `$date->copy()->subDays((int) $days)` |

## Error Prevention

1. **Always validate input**: Use Laravel's validation rules to ensure correct data types
2. **Use model casts**: Define proper casts in your Eloquent models
3. **Add mutators**: For critical fields, add mutators to enforce type safety
4. **Test thoroughly**: Write unit tests for all date operations
5. **Code review**: Always review Carbon usage in pull requests

By following these practices, you can prevent Carbon type errors and ensure consistent, reliable date operations throughout your application.
