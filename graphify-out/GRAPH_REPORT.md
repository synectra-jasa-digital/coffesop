# Graph Report - .  (2026-09-02)

## Corpus Check
- Corpus is ~32,361 words - fits in a single context window. You may not need a graph.

## Summary
- 709 nodes · 1020 edges · 124 communities (103 shown, 21 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 6 edges (avg confidence: 0.83)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Livewire Dashboard
- Composer Dependencies
- Product Controller
- Build Tools & Assets
- Artisan Scripts
- Activity Logging
- User CRUD
- Authentication Tests
- Product CRUD
- Login Flow
- PRD & System Specs
- Cart Business Logic
- Category Controller
- Recipe Management
- Table & Category Modals
- Stock Opname
- Ingredient Management
- Settings Configuration
- Roles & Permissions
- Model Factories
- Database Seeders
- Service Providers
- Layout Components
- Password Reset Tests
- Users & Permissions Migrations
- Cache & Settings Migrations
- Jobs & Tables Migrations
- Email Verification Tests
- POS Modal Interactions
- Laravel Boost Setup
- Laravel Bootstrap
- Cart Blade UI
- Auth Screen Tests
- Profile Tests
- Stock Movement
- Shift Manager UI
- Logging Configuration
- Product Blade UI
- Ingredient Blade UI
- User Management UI
- Order History UI
- Example Test
- Profile Blade Forms
- Console Kernel
- Terminal POS View
- Verify Email View
- Dashboard Stats View
- Broadcasting Config
- Layout Navigation
- POS Shift Manager View
- Robots.txt
- Recipe Manager View
- KDS Display View
- Navigation View
- Login View

## God Nodes (most connected - your core abstractions)
1. `User` - 58 edges
2. `Product` - 33 edges
3. `Category` - 28 edges
4. `Ingredient` - 24 edges
5. `Order` - 24 edges
6. `TestCase` - 18 edges
7. `Recipe` - 12 edges
8. `Table` - 12 edges
9. `RecipeIngredient` - 11 edges
10. `Shift` - 11 edges

## Surprising Connections (you probably didn't know these)
- `Laravel Boost Guidelines` --semantically_similar_to--> `Laravel Boost Guidelines`  [INFERRED] [semantically similar]
  CLAUDE.md → AGENTS.md
- `Premium Editorial UI` --semantically_similar_to--> `Craft-First UI`  [INFERRED] [semantically similar]
  rules/design.md → PRODUCT.md
- `Real-Time KDS` --semantically_similar_to--> `Real-Time Web Application`  [INFERRED] [semantically similar]
  README.md → rules/PRD-Aplikasi-POS-Coffee-Shop.md
- `BOM-Based Stock Deduction` --conceptually_related_to--> `Real-Time KDS Updates`  [INFERRED]
  rules/PRD-Aplikasi-POS-Coffee-Shop.md → PRODUCT.md
- `CategoryController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/CategoryController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **POS System Architecture** — prd_pos_terpadu_coffee_shop, product_good_coffee_pos, readme_good_coffee_pos_kds_system [INFERRED 0.85]
- **Brand Design Alignment** — product_craft_first_ui, rules_design_premium_editorial_ui, rules_design_teal_green_palette [INFERRED 0.75]

## Communities (124 total, 21 thin omitted)

### Community 0 - "Livewire Dashboard"
Cohesion: 0.07
Nodes (14): Index, DashboardStats, Display, History, ShiftManager, Terminal, Order, Shift (+6 more)

### Community 1 - "Composer Dependencies"
Cohesion: 0.04
Nodes (45): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+37 more)

### Community 2 - "Product Controller"
Cohesion: 0.08
Nodes (12): VerifyEmailController, Controller, ProductController, StoreCategoryRequest, StoreProductRequest, UpdateCategoryRequest, UpdateProductRequest, Illuminate\Auth\Events\Verified (+4 more)

### Community 3 - "Build Tools & Assets"
Cohesion: 0.06
Nodes (30): autoprefixer, concurrently, laravel-echo, @laravel/multiplex, laravel-vite-plugin, devDependencies, autoprefixer, concurrently (+22 more)

### Community 4 - "Artisan Scripts"
Cohesion: 0.08
Nodes (26): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+18 more)

### Community 5 - "Activity Logging"
Cohesion: 0.16
Nodes (7): ActivityLog, Discount, ProductVariant, Supplier, Transaction, Illuminate\Database\Eloquent\Factories\HasFactory, Illuminate\Database\Eloquent\Model

### Community 6 - "User CRUD"
Cohesion: 0.13
Nodes (8): Index, User, CategoryPolicy, Illuminate\Database\Eloquent\Attributes\Fillable, Illuminate\Database\Eloquent\Attributes\Hidden, Illuminate\Foundation\Auth\User, Illuminate\Notifications\Notifiable, Spatie\Permission\Traits\HasRoles

### Community 7 - "Authentication Tests"
Cohesion: 0.15
Nodes (8): Illuminate\Foundation\Testing\RefreshDatabase, Illuminate\Foundation\Testing\TestCase, Livewire\Volt\Volt, PasswordConfirmationTest, PasswordUpdateTest, RegistrationTest, ExampleTest, TestCase

### Community 8 - "Product CRUD"
Cohesion: 0.13
Nodes (4): Index, Product, ProductPolicy, Illuminate\Auth\Access\Response

### Community 9 - "Login Flow"
Cohesion: 0.12
Nodes (11): Logout, LoginForm, Illuminate\Auth\Events\Lockout, Illuminate\Support\Facades\Auth, Illuminate\Support\Facades\RateLimiter, Illuminate\Support\Facades\Session, Illuminate\Support\Str, Illuminate\Validation\ValidationException (+3 more)

### Community 10 - "PRD & System Specs"
Cohesion: 0.11
Nodes (20): BOM-Based Stock Deduction, Kitchen Display System, Sistem POS Terpadu Coffee Shop, Real-Time Web Application, Role-Based Access Control, Single Outlet Scope, Craft-First UI, Good Coffee POS (+12 more)

### Community 11 - "Cart Business Logic"
Cohesion: 0.18
Nodes (3): Cart, OrderItem, Payment

### Community 12 - "Category Controller"
Cohesion: 0.18
Nodes (3): CategoryController, MenuGrid, Category

### Community 13 - "Recipe Management"
Cohesion: 0.17
Nodes (3): RecipeManager, Recipe, RecipeIngredient

### Community 14 - "Table & Category Modals"
Cohesion: 0.21
Nodes (3): Index, Table, DemoDataSeeder

### Community 17 - "Settings Configuration"
Cohesion: 0.27
Nodes (3): Index, Setting, CoffeeShopSeeder

### Community 18 - "Roles & Permissions"
Cohesion: 0.22
Nodes (6): RolesAndPermissionsSeeder, Illuminate\Support\Facades\Hash, Illuminate\Validation\Rules\Password, Spatie\Permission\DefaultTeamResolver, Spatie\Permission\Models\Permission, Spatie\Permission\Models\Role

### Community 19 - "Model Factories"
Cohesion: 0.24
Nodes (5): CategoryFactory, ProductFactory, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, static

### Community 20 - "Database Seeders"
Cohesion: 0.24
Nodes (5): CategorySeeder, DatabaseSeeder, ProductSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Seeder

### Community 21 - "Service Providers"
Cohesion: 0.28
Nodes (3): AppServiceProvider, VoltServiceProvider, Illuminate\Support\ServiceProvider

### Community 22 - "Layout Components"
Cohesion: 0.43
Nodes (4): AppLayout, GuestLayout, Illuminate\View\Component, Illuminate\View\View

### Community 23 - "Password Reset Tests"
Cohesion: 0.25
Nodes (3): Illuminate\Auth\Notifications\ResetPassword, Illuminate\Support\Facades\Notification, PasswordResetTest

### Community 27 - "Email Verification Tests"
Cohesion: 0.29
Nodes (3): Illuminate\Support\Facades\Event, Illuminate\Support\Facades\URL, EmailVerificationTest

### Community 28 - "POS Modal Interactions"
Cohesion: 0.29
Nodes (6): openCategoryModal, openCategoryModal({{ $cat->id }}), openTableModal, openTableModal({{ $table->id }}), $set(, switchTab(

### Community 29 - "Laravel Boost Setup"
Cohesion: 0.33
Nodes (6): Laravel Application, Laravel Boost Guidelines, Laravel Boost Installation, PHP and Composer Prerequisites, Laravel Application, Laravel Boost Guidelines

### Community 30 - "Laravel Bootstrap"
Cohesion: 0.40
Nodes (4): Illuminate\Foundation\Application, Illuminate\Foundation\Configuration\Exceptions, Illuminate\Foundation\Configuration\Middleware, Illuminate\Http\Request

### Community 31 - "Cart Blade UI"
Cohesion: 0.33
Nodes (5): clearCart, decrementQuantity({{ $index }}), incrementQuantity({{ $index }}), processCheckout, $set(

### Community 35 - "Shift Manager UI"
Cohesion: 0.40
Nodes (4): closeShift, initiateCloseShift, openShift, $set(

### Community 36 - "Logging Configuration"
Cohesion: 0.40
Nodes (4): Monolog\Handler\NullHandler, Monolog\Handler\StreamHandler, Monolog\Handler\SyslogUdpHandler, Monolog\Processor\PsrLogMessageProcessor

### Community 37 - "Product Blade UI"
Cohesion: 0.40
Nodes (4): edit({{ $product->id }}), create, $set(, toggleStatus({{ $product->id }})

### Community 38 - "Ingredient Blade UI"
Cohesion: 0.40
Nodes (4): openAddIngredient, openOpname, openStockIn, $set(

### Community 40 - "User Management UI"
Cohesion: 0.50
Nodes (3): edit({{ $user->id }}), create, $set(

### Community 41 - "Order History UI"
Cohesion: 0.50
Nodes (3): openDetail({{ $order->id }}), openVoid({{ $selectedOrder->id }}), $set(

### Community 43 - "Profile Blade Forms"
Cohesion: 0.50
Nodes (3): profile.delete-user-form, profile.update-password-form, profile.update-profile-information-form

## Knowledge Gaps
- **123 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+118 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **21 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `User CRUD` to `Auth Screen Tests`, `Profile Tests`, `Activity Logging`, `Authentication Tests`, `Product CRUD`, `Settings Configuration`, `Roles & Permissions`, `Password Reset Tests`, `Email Verification Tests`?**
  _High betweenness centrality (0.100) - this node is a cross-community bridge._
- **Why does `Product` connect `Product CRUD` to `Livewire Dashboard`, `Product Controller`, `Activity Logging`, `Category Controller`, `Recipe Management`, `Settings Configuration`, `Model Factories`, `Database Seeders`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **Why does `Category` connect `Category Controller` to `Livewire Dashboard`, `Activity Logging`, `User CRUD`, `Product CRUD`, `Table & Category Modals`, `Settings Configuration`, `Model Factories`, `Database Seeders`?**
  _High betweenness centrality (0.039) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _123 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Livewire Dashboard` be split into smaller, more focused modules?**
  _Cohesion score 0.06717687074829932 - nodes in this community are weakly interconnected._
- **Should `Composer Dependencies` be split into smaller, more focused modules?**
  _Cohesion score 0.043478260869565216 - nodes in this community are weakly interconnected._
- **Should `Product Controller` be split into smaller, more focused modules?**
  _Cohesion score 0.0761904761904762 - nodes in this community are weakly interconnected._