<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property numeric|null $cost_price
 * @property numeric $price
 * @property numeric $packaging_charge
 * @property int $include_tax
 * @property int $include_service_charge
 * @property string $status
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereIncludeServiceCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereIncludeTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn wherePackagingCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AddOn whereUpdatedAt($value)
 */
	class AddOn extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $advance_number
 * @property int|null $order_id
 * @property int|null $reservation_id
 * @property int|null $customer_id
 * @property int|null $payment_method_id
 * @property int $branch_id
 * @property int $order_type_id
 * @property string $type
 * @property int|null $reference_id
 * @property string $direction
 * @property numeric $amount
 * @property numeric|null $balance_amount
 * @property string|null $purpose
 * @property string|null $advance_date
 * @property string|null $event_date
 * @property string|null $notes
 * @property string $status
 * @property int $is_cancelled
 * @property int|null $cancelled_by
 * @property string|null $cancellation_reason
 * @property string|null $cancelled_at
 * @property int|null $user_id
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereAdvanceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereAdvanceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereBalanceAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereCancelledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereEventDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereIsCancelled($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereReservationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Advance whereUserId($value)
 */
	class Advance extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $legal_name
 * @property string|null $address_line_1
 * @property string|null $address_line_2
 * @property string|null $city
 * @property string|null $district
 * @property string|null $province
 * @property string|null $country
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $currency_code
 * @property int|null $tax_profile_id
 * @property int $is_active
 * @property int $is_live
 * @property int|null $created_by
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereAddressLine1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereAddressLine2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereCurrencyCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereDistrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsLive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereLegalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereTaxProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Branch whereUpdatedAt($value)
 */
	class Branch extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $user_id
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category whereUserId($value)
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postal_code
 * @property string|null $country
 * @property string $password
 * @property string|null $google_id
 * @property string|null $avatar
 * @property string $role
 * @property string $status
 * @property string|null $email_verified_at
 * @property string|null $remember_token
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $deposit_item_name
 * @property string $deposit_item_description
 * @property numeric $deposit_price
 * @property string|null $description
 * @property int $is_active
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereDepositItemDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereDepositItemName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereDepositPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositItem whereUpdatedAt($value)
 */
	class DepositItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $cusromer_id
 * @property int $deposit_item_id
 * @property int|null $item_id
 * @property int|null $extra_service_id
 * @property int|null $order_id
 * @property int $quantity
 * @property numeric $deposit_amount
 * @property string|null $return_date
 * @property numeric|null $refund_amount
 * @property string|null $refund_date
 * @property string $status
 * @property string|null $notes
 * @property int $isOverdue
 * @property string|null $refund_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereCusromerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereDepositAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereDepositItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereExtraServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereIsOverdue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereRefundBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereRefundDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereReturnDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DepositTransaction whereUpdatedAt($value)
 */
	class DepositTransaction extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $serviceName
 * @property string|null $description
 * @property numeric $default_price
 * @property numeric|null $cost_price
 * @property int $is_tax_included
 * @property int $is_service_charge_included
 * @property string $applicable_order_types_id
 * @property int $is_active
 * @property string|null $measure_unit
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereApplicableOrderTypesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereDefaultPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereIsServiceChargeIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereIsTaxIncluded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereMeasureUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereServiceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraService whereUpdatedAt($value)
 */
	class ExtraService extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $category_id
 * @property int|null $variant_id
 * @property int|null $portions_id
 * @property string $item_type
 * @property string $base_name
 * @property string|null $full_name
 * @property string|null $description
 * @property string $status
 * @property int $kitchen_id
 * @property string|null $image_url
 * @property int $include_tax
 * @property int $include_service_charge
 * @property int $user_id
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemPrice> $ItemPrices
 * @property-read int|null $item_prices_count
 * @property-read \App\Models\Category|null $category
 * @property-read \App\Models\Kitchen|null $kitchen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\Portion|null $portion
 * @property-read \App\Models\User|null $user
 * @property-read \App\Models\Variant|null $variant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereBaseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereIncludeServiceCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereIncludeTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereKitchenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item wherePortionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item whereVariantId($value)
 */
	class Item extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $item_id
 * @property int|null $variant_id
 * @property int|null $portion_id
 * @property int $order_type_id
 * @property numeric|null $cost_price
 * @property numeric $price
 * @property numeric|null $package_charge
 * @property int $have_deposit_item
 * @property int|null $deposit_item_id
 * @property numeric|null $deposit_amount
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item|null $item
 * @property-read \App\Models\OrderType|null $orderType
 * @property-read \App\Models\Portion|null $portion
 * @property-read \App\Models\Variant|null $variant
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereDepositAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereDepositItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereHaveDepositItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice wherePackageCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice wherePortionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemPrice whereVariantId($value)
 */
	class ItemPrice extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $affected_stock
 * @property int $is_sellable
 * @property int $requires_recipe
 * @property int $is_active
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereAffectedStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereIsSellable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereRequiresRecipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemTypes whereUpdatedAt($value)
 */
	class ItemTypes extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $location
 * @property string $status
 * @property int $user_id
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kitchen whereUserId($value)
 */
	class Kitchen extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $branch_id
 * @property int|null $sub_order_id
 * @property string $order_number
 * @property int|null $table_id
 * @property int|null $table_merge_id
 * @property int $user_id
 * @property int $steward_id
 * @property int $order_type_id
 * @property string|null $customer_name
 * @property string|null $customer_id
 * @property string $status
 * @property int|null $reserved_seats
 * @property numeric $total_amount
 * @property numeric $discount_amount
 * @property numeric $tax_amount
 * @property numeric $service_charge
 * @property numeric $packaging_charge
 * @property string $payment_status
 * @property string|null $payment_method
 * @property string|null $opened_at
 * @property string|null $confirmed_at
 * @property string|null $prepared_at
 * @property string|null $served_at
 * @property string|null $closed_at
 * @property string|null $cancelled_at
 * @property string|null $notes
 * @property int|null $cashier_id
 * @property int|null $opened_by
 * @property int|null $closed_by
 * @property string|null $void_reason
 * @property string|null $cancel_reason
 * @property int|null $cancelled_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $cancelledBy
 * @property-read \App\Models\User|null $closedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $openedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \App\Models\OrderType|null $orderType
 * @property-read \App\Models\Steward $steward
 * @property-read \App\Models\Table|null $table
 * @property-read \App\Models\TableMerge|null $tableMerge
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCancelReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCancelledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCashierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereClosedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereClosedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOpenedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOpenedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePackagingCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePreparedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereReservedSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereServedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereServiceCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStewardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereSubOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTableMergeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereVoidReason($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $order_type_id
 * @property int $item_id
 * @property int|null $extra_service_id
 * @property int|null $package_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $subtotal
 * @property numeric $discount_amount
 * @property numeric $discount_percentage
 * @property numeric $service_charge_amount
 * @property numeric $service_charge_percentage
 * @property numeric $tax_amount
 * @property numeric $tax_percentage
 * @property string|null $notes
 * @property int|null $kitchen_id
 * @property string $status
 * @property string|null $added_at
 * @property string|null $accepted_at
 * @property string|null $started_at
 * @property string|null $completed_at
 * @property string|null $cancelled_at
 * @property int $is_voided
 * @property string $void_reason
 * @property int|null $voided_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\Kitchen|null $kitchen
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\OrderType $orderType
 * @property-read \App\Models\Portion|null $portion
 * @property-read \App\Models\Variant|null $variant
 * @property-read \App\Models\User|null $voidedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereAddedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereExtraServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereIsVoided($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereKitchenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereOrderTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereServiceChargeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereServiceChargePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereVoidReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItem whereVoidedBy($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $order_id
 * @property int $order_item_id
 * @property int $add_on_id
 * @property int $quantity
 * @property numeric $unit_price
 * @property numeric $total_price
 * @property numeric $packaging_charge
 * @property numeric $discount_amount
 * @property numeric $tax_amount
 * @property numeric $service_charge_amount
 * @property numeric $total_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereAddOnId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn wherePackagingCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereServiceChargeAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderItemAddOn whereUpdatedAt($value)
 */
	class OrderItemAddOn extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property int $requires_table
 * @property int $allow_service_charge
 * @property int $allow_packaging_charge
 * @property int $allow_split_payment
 * @property int $allow_discounts
 * @property int $tax_inclusive
 * @property int $steward_required
 * @property int $is_active
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemPrice> $itemPrices
 * @property-read int|null $item_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $order
 * @property-read int|null $order_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItem
 * @property-read int|null $order_item_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereAllowDiscounts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereAllowPackagingCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereAllowServiceCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereAllowSplitPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereRequiresTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereStewardRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereTaxInclusive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderType whereUpdatedAt($value)
 */
	class OrderType extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property numeric $cost_price
 * @property numeric $actual_price
 * @property numeric|null $discounted_price
 * @property numeric $price
 * @property string $status
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereActualPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereDiscountedPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Package whereUpdatedAt($value)
 */
	class Package extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $package_id
 * @property int|null $item_id
 * @property int|null $extra_service_id
 * @property int $quantity
 * @property numeric|null $cost_price
 * @property numeric $price
 * @property string $status
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExtraService|null $extraService
 * @property-read \App\Models\Item|null $item
 * @property-read \App\Models\Package $package
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereExtraServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PackageItem whereUpdatedAt($value)
 */
	class PackageItem extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $type
 * @property int $is_online
 * @property int $requires_reference
 * @property int $is_active
 * @property int $sort_order
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereRequiresReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaymentMethod whereUpdatedAt($value)
 */
	class PaymentMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property numeric|null $multiplier
 * @property numeric|null $additional_price
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemPrice> $itemPrices
 * @property-read int|null $item_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereAdditionalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereMultiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Portion whereUpdatedAt($value)
 */
	class Portion extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string $description
 * @property int $is_synced
 * @property string|null $synced_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereSyncedDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUserId($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $branch_id
 * @property string $name
 * @property string $employee_id
 * @property int $is_active
 * @property string $status
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Steward whereUserId($value)
 */
	class Steward extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $location
 * @property numeric $tax_percentage
 * @property numeric $service_charge_percentage
 * @property string $key
 * @property string|null $value
 * @property string|null $group
 * @property string $data_type
 * @property int $is_editable
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereDataType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereIsEditable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereServiceChargePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereTaxPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemSetting whereValue($value)
 */
	class SystemSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $table_number
 * @property int $capacity
 * @property int $reserved_seats
 * @property string $zone
 * @property string $status
 * @property string|null $description
 * @property string|null $location
 * @property string $table_type
 * @property int|null $min_occupancy
 * @property int|null $max_occupancy
 * @property array<array-key, mixed>|null $features
 * @property int $user_id
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $available_seats
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TableMerge> $tableMergesAsTable1
 * @property-read int|null $table_merges_as_table1_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TableMerge> $tableMergesAsTable2
 * @property-read int|null $table_merges_as_table2_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TableMerge> $tableMergesAsTable3
 * @property-read int|null $table_merges_as_table3_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereMaxOccupancy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereMinOccupancy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereReservedSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereTableNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereTableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Table whereZone($value)
 */
	class Table extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $table_id_1
 * @property int $table_id_2
 * @property int|null $table_id_3
 * @property string $merged_at
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $order
 * @property-read int|null $order_count
 * @property-read \App\Models\Table|null $table1
 * @property-read \App\Models\Table|null $table2
 * @property-read \App\Models\Table|null $table3
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereMergedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereTableId1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereTableId2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereTableId3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TableMerge whereUserId($value)
 */
	class TableMerge extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $google_id
 * @property string|null $avatar
 * @property int|null $branch_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $address
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $status
 * @property int|null $user_id
 * @property string|null $remember_token
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $phone
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Kitchen> $kitchens
 * @property-read int|null $kitchens_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TableMerge> $tableMerges
 * @property-read int|null $table_merges_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Table> $tables
 * @property-read int|null $tables_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserId($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property numeric|null $multiplier
 * @property numeric|null $additional_price
 * @property int $is_synced
 * @property string|null $sync_date_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ItemPrice> $ItemPrices
 * @property-read int|null $item_prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $Items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereAdditionalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereMultiplier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereSyncDateTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Variant whereUpdatedAt($value)
 */
	class Variant extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $item_id
 * @property int $order_id
 * @property int $branch_id
 * @property string $reason
 * @property int|null $approved_by
 * @property string|null $voided_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VoidOrder whereVoidedAt($value)
 */
	class VoidOrder extends \Eloquent {}
}

