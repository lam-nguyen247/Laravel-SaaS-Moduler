# Laravel

## Command

Cách sử dụng command trong module

```cmd
php artisan make:module {Feature} {type} {name}
vd:
php artisan make:module Subscription controller SubscriptionController
php artisan make:module Subscription model Subscription
```

## Giải thích cấu trúc:

1. `/saas-app`: Thư mục gốc của dự án Laravel của bạn.

2. `/app`: Chứa các tệp tin PHP chính của ứng dụng.

    - `/Modules`: Thư mục chứa các module của ứng dụng. Mỗi module có các phần con riêng như Controllers, Models, Services, Views để quản lý tính năng cụ thể.

        - `/Subscription`: Module Subscription chứa các thành phần liên quan đến việc quản lý đăng ký.

            - `/Controllers`: Chứa các controllers liên quan đến việc quản lý đăng ký.
            - `/Models`: Chứa các models liên quan đến việc quản lý đăng ký.
            - `/Services`: Chứa các services hỗ trợ quản lý đăng ký.
            - `/Views`: Chứa các tệp Blade templates cho việc hiển thị giao diện người dùng.

        - `/Payment`: Module Payment chứa các thành phần liên quan đến việc quản lý thanh toán.

            - `/Controllers`: Chứa các controllers liên quan đến việc quản lý thanh toán.
            - `/Models`: Chứa các models liên quan đến việc quản lý thanh toán.
            - `/Services`: Chứa các services hỗ trợ quản lý thanh toán.
            - `/Views`: Chứa các tệp Blade templates cho việc hiển thị giao diện người dùng.

        - `/Notification`: Module Notification chứa các thành phần liên quan đến việc quản lý thông báo.

            - `/Controllers`: Chứa các controllers liên quan đến việc quản lý thông báo.
            - `/Models`: Chứa các models liên quan đến việc quản lý thông báo.
            - `/Services`: Chứa các services hỗ trợ quản lý thông báo.
            - `/Views`: Chứa các tệp Blade templates cho việc hiển thị giao diện người dùng.

3. `/config`: Chứa các tệp tin cấu hình của ứng dụng.

    - `saas.php`: Tệp tin cấu hình đặc biệt cho ứng dụng SaaS của bạn.

4. `/database`: Chứa các tệp tin liên quan đến cơ sở dữ liệu.

    - `/migrations`: Chứa các tệp tin migration để quản lý cấu trúc cơ sở dữ liệu.
    - `/seeds`: Chứa các tệp tin seeder để cung cấp dữ liệu mẫu.

5. `/resources`: Chứa các tệp nguồn của ứng dụng.

    - `/lang`: Chứa các tệp tin ngôn ngữ.

        - `/en`: Chứa các tệp tin ngôn ngữ cho tiếng Anh.

6. `/routes`: Chứa các tệp tin định tuyến của ứng dụng.

    - `web.php`: Định tuyến cho các tương tác người dùng thông qua trình duyệt.
    - `api.php`: Định tuyến cho các API.

7. `/tests`: Chứa các tệp tin kiểm thử.

    - `/Feature`: Chứa các kiểm thử tích hợp.

        - `/Subscription`: Chứa các kiểm thử liên quan đến module Subscription.
        - `/Payment`: Chứa các kiểm thử liên quan đến module Payment.
        - `/Notification`: Chứa các kiểm thử liên quan đến module Notification.

8. `/public`: Chứa các tệp tin tĩnh như CSS, JavaScript, hình ảnh mà trình duyệt có thể truy cập.

9. `/storage`: Chứa các tệp tin tạo ra bởi ứng dụng như hình ảnh tải lên, log.

10. `/vendor`: Chứa các thư viện và gói mở rộng của ứng dụng.

11. `.env`: Tệp tin cấu hình môi trường.

## 2. Quy tắc đặt tên

### Controllers

-   Tên controller nên có tên gọi rõ ràng, sử dụng dạng CamelCase, kết thúc bằng "Controller". Ví dụ: `UserController.php`.

### Models

-   Tên model nên có tên gọi rõ ràng, sử dụng dạng CamelCase và số ít. Ví dụ: `User.php`.

### Routes

-   Tên route nên sử dụng dạng kebab-case và phản ánh tính năng hoặc hành động mà nó thực hiện: <br>
    {module}.{controller}.{action} <br>
    Ví dụ: <br>
    `super-admin.auth.user-profile`.

### Views

-   Tên tệp tin view nên sử dụng dạng kebab-case và phản ánh tính năng hoặc hành động mà nó thể hiện. Ví dụ: `user-profile.blade.php`.

## 3. Quy tắc viết code

-   Sử dụng 4 khoảng trắng để thụt đầu dòng (không sử dụng tab).
-   Sử dụng chuẩn PSR-2 cho việc định dạng code PHP.
-   Tránh sử dụng các hằng số ma thuật (magic numbers) mà không giải thích rõ ràng.

## 4. Sử dụng các best practice của Laravel

-   Sử dụng dependency injection.
-   Sử dụng named routes.
-   Sử dụng Eloquent relationships.
-   Sử dụng Eloquent scopes để truy vấn dữ liệu.

## 5. Quy tắc kiểm thử

-   Viết kiểm thử đầy đủ và chính xác cho từng tính năng hoặc chức năng của ứng dụng.

# Quy ước Viết Mã (Coding Convention) cho PHP 8

## 1. Cú Pháp

-   Sử dụng cú pháp chuẩn của PHP 8: `<?php ... ?>` cho mã PHP và `<?= ... ?>` cho xuất dữ liệu ngắn gọn.

## 2. Đặt Tên

-   Sử dụng quy tắc đặt tên PSR-12.

    -   Class: `PascalCase`
    -   Function và Method: `camelCase`
    -   Biến: `camelCase`
    -   Hằng: `UPPER_CASE`

## 3. Khai Báo

-   Mỗi lệnh namespace và use phải nằm trên một dòng riêng biệt.

-   Khai báo tên lớp, phương thức và biến phải được đi kèm với các chú thích dòng một (docblocks) để mô tả chức năng của chúng.

## 4. Gán Giá Trị Mặc Định

-   Khi khai báo hàm có tham số, sử dụng gán giá trị mặc định.

```php
function example($param1 = 'default', $param2 = null) {
    // ...
}
```

## 5. Loops và Conditions

-   Sử dụng dấu ngoặc mở đầu dòng cho if, else if, else, for, foreach, while, và switch.

```php
if ($condition) {
    // ...
} elseif ($anotherCondition) {
    // ...
} else {
    // ...
}

for ($i = 0; $i < $count; $i++) {
    // ...
}
```

## 6. Khoảng Trắng và Thụt Đầu Dòng

-   Sử dụng 4 dấu cách (space) để thụt đầu dòng.

-   Sử dụng 1 dấu cách (space) trước và sau các toán tử.

```php
$variable = $otherVariable + 3;
```

## 7. Dòng Trắng

-   Sử dụng dòng trắng để phân tách các khối mã, chẳng hạn giữa các phần khai báo và các phần thân hàm.

## 8. Kết Thúc Dòng

-   Sử dụng LF (Line Feed) như ký tự kết thúc dòng.

## 9. Kích Thước Tệp

-   Mỗi tệp PHP nên chứa một lớp hoặc một giao diện duy nhất.

## 10. Tài Liệu

-   Sử dụng các chú thích docblocks để tài liệu mã nguồn của bạn.

```php
/**
 * This is a docblock comment.
 *
 * @param int $param1 Description of parameter.
 * @return bool Description of return value.
 */
function example($param1) {
    // ...
}

```

## 11. Sử Dụng PHP 8 Cú Pháp

-   Tận dụng các tính năng mới của PHP 8, bao gồm typed properties, match expression, nullsafe operator, và các cải tiến khác.

```php
public int $count;

$status = match ($value) {
    1 => 'One',
    2 => 'Two',
    default => 'Other',
};

$result = $object?->property ?? 'default';

```

# Quy trình kiểm tra coding convention trước khi push commit

## Chạy tool auto fix với Laravel Pint

```
//Chạy lệnh composer trong môi trường docker hoặc local
composer pint-fixed
```

## Kiểm tra sâu với PHPStan

```
//Chạy lệnh composer trong môi trường docker hoặc local
composer check
```

Để bỏ qua việc kiểm tra một số dòng code cụ thể bởi PHPStan, bạn có thể sử dụng một trong những cách sau:

Sử dụng PHPDoc Comments: Bạn có thể sử dụng comment PHPDoc để bảo PHPStan bỏ qua một dòng hoặc một khối code cụ thể. Để làm điều này, thêm một comment `@phpstan-ignore-line` vào cuối dòng mà bạn muốn bỏ qua, hoặc `@phpstan-ignore-next-line` trước dòng đó.

```php
$result = someFunction(); // @phpstan-ignore-line

// @phpstan-ignore-next-line
$result = someFunction();

```
