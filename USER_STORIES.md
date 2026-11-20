# LaraGym Software Requirements Specification (SRS) & Manual QA User Stories
**Project:** LaraGym (Gym Management System & Member PWA)  
**Roles:** Senior Business Analyst, Product Owner & QA Lead  
**Scope:** Manual Software Testing Training Project  

---

## 1. Project Overview & Architecture

### 1.1 Application Purpose & Business Domain
LaraGym is a multi-tier gym and fitness club management application. It handles administrative club operations—including membership packages, billing cycles, club amenities/services, multi-branch facilities, subscription lifecycle management, front-desk attendance check-in kiosk, and system audit logs—as well as a dedicated mobile-first Member Progressive Web App (PWA) for member self-service.

### 1.2 Target User Roles
1. **Gym Administrator / Staff (`is_admin = true`)**:
   - Accesses the desktop **Admin Panel** (`http://127.0.0.1:5173`).
   - Manages physical facilities (Branches), catalog definitions (Services, Billing Cycles, Packages), member accounts, member branch assignments, subscriptions, staff-assisted check-in/check-out kiosk logging, system activity trails, and business KPIs.
2. **Gym Member (`is_admin = false`)**:
   - Accesses the mobile-optimized **Member PWA** (`http://127.0.0.1:5174`).
   - Self-registers, manages personal contact and demographic profiles, changes credentials, reviews assigned gym branches, monitors active and historical membership subscriptions, and tracks gym visit check-in records.

### 1.3 Major Functional Modules
1. **Authentication & Identity Access Management (Admin & Member)**
2. **Executive Dashboard & Front-Desk Kiosk (Admin)**
3. **Billing Cycles Management (Admin)**
4. **Gym Services Catalog Management (Admin)**
5. **Membership Packages Management (Admin)**
6. **Branch Facility Management (Admin & Member View)**
7. **Member Account & Profile Management (Admin)**
8. **Member Branch Assignment & Security (Admin)**
9. **Subscriptions Management (Admin)**
10. **Attendance & Check-in Tracking (Admin Kiosk & Member View)**
11. **System Activity Logs & Audit Trail (Admin)**
12. **Member Self-Service Portal (Member PWA)**

---

## 2. Requirements Traceability Matrix

| Module | Implemented Functionality | Story ID | Acceptance Criteria IDs |
|---|---|---|---|
| **Auth & Security** | Staff & Admin Login & Authorization | `US-001` | `AC-001` – `AC-006` |
| **Auth & Security** | Member Self-Registration & Account Number Generation | `US-002` | `AC-007` – `AC-011` |
| **Auth & Security** | Member PWA Login, Logout, Route Guarding | `US-003` | `AC-012` – `AC-014` |
| **Auth & Security** | Forgot Password Request Dispatch | `US-004` | `AC-015` – `AC-017` |
| **Auth & Security** | Password Reset Execution with Security Token | `US-005` | `AC-018` – `AC-021` |
| **Dashboard** | Executive Real-Time KPI Cards | `US-006` | `AC-022` – `AC-024` |
| **Front Desk** | Member Search & Kiosk Check-In/Check-Out Logging | `US-007` | `AC-025` – `AC-028` |
| **Billing Cycles** | Billing Cycles Directory, Search, Pagination | `US-008` | `AC-029` – `AC-031` |
| **Billing Cycles** | Cycle Create, Edit, Soft Delete | `US-009` | `AC-032` – `AC-035` |
| **Services** | Gym Services Catalog CRUD & Filtering | `US-010` | `AC-036` – `AC-039` |
| **Branches** | Branch Facility Management CRUD | `US-011` | `AC-040` – `AC-043` |
| **Packages** | Membership Package Bundling, Pricing, Services Sync | `US-012` | `AC-044` – `AC-048` |
| **Member CRM** | Admin Walk-in Member Creation | `US-013` | `AC-049` – `AC-051` |
| **Member CRM** | Member Profile Details & Status Updates | `US-014` | `AC-052` – `AC-054` |
| **Member CRM** | Member Profile Avatar Photo Upload & Resizing | `US-015` | `AC-055` – `AC-057` |
| **Member CRM** | Admin Password Reset for Members | `US-016` | `AC-058` – `AC-060` |
| **Member CRM** | Member Multi-Branch Allocation | `US-017` | `AC-061` – `AC-063` |
| **Member CRM** | Member Soft Deletion & Self-Protection Rule | `US-018` | `AC-064`, `AC-065` |
| **Subscriptions** | Issue Subscription with Auto Expiration Calculation | `US-019` | `AC-066` – `AC-069` |
| **Subscriptions** | Lifecycle Status Transitions & Suspension Timestamp | `US-020` | `AC-070` – `AC-073` |
| **Audit Logs** | System Activity Log Sorting & Filtering | `US-021` | `AC-074` – `AC-076` |
| **Member PWA** | Member Self-Service Demographic Update | `US-022` | `AC-077` – `AC-079` |
| **Member PWA** | Member Self-Service Password Change | `US-023` | `AC-080` – `AC-083` |
| **Member PWA** | Member Subscription Details & Inclusions View | `US-024` | `AC-084` – `AC-086` |
| **Member PWA** | Member Attendance History & Month Filter | `US-025` | `AC-087` – `AC-089` |
| **Member PWA** | Member Branch Directory & Access Badge | `US-026` | `AC-090` – `AC-092` |

---

## 3. Detailed User Stories

---

### US-001: Staff & Admin Authentication
- **Module:** Authentication & Access Control
- **Priority:** High
- **User Role:** Gym Administrator / Front-Desk Staff
- **Story Type:** Security / Functional

#### User Story Statement
As a **Gym Administrator**,  
I want to **authenticate into the Admin Panel using my registered email and password**,  
So that **I can securely access backend club management tools, member data, and administrative operations.**

#### Business Context
The Admin Panel controls sensitive financial and personal member records. Unauthorized users or regular gym members must not access the back-office management panel.

#### Scope
- **In Scope:** Admin login form on port 5173, credential validation against the backend, role verification (`is_admin = true`), session cookie establishment, redirect to Dashboard `/`.
- **Out of Scope:** Member PWA login (handled in `US-003`), password recovery (handled in `US-004`).

#### Preconditions
- The LaraGym backend API and Admin web app are running.
- A user account with `is_admin = true` and `status = active` exists.

#### Detailed Functional Description
The administrator navigates to `/login`. The system presents email and password input fields. Upon clicking "Submit", the frontend transmits credentials to the authentication service. If valid and `is_admin` is true, an access token is issued and stored in an HTTP cookie, a success toast "You have successfully logged in. Redirecting..." is displayed, and the browser redirects to `/` after 1.5 seconds. If credentials fail or `is_admin` is false, an error notification is presented, and the user remains unauthenticated.

#### Business Rules
- **BR-001 (Admin Privilege Requirement):** Only accounts with `is_admin = true` are authorized to log into the Admin portal. Regular members attempting login must be rejected with an authorization error.
- **BR-002 (Account Status Requirement):** Inactive or suspended accounts cannot authenticate.
- **BR-003 (Password Length Constraint):** Passwords must be between 5 and 20 characters in length.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Email | Admin account email | Yes | String | Valid email format (`name@domain.ext`) | Must exist in `users` table | `admin@admin.com` |
| Password | Admin account password | Yes | String | Plain text string | Min 5, Max 20 characters | `password` |

#### Acceptance Criteria

**AC-001: Successful administrator login and redirection**
- **Scenario:** Valid administrator submits verified credentials.
- **Conditions:** Email belongs to an existing user with `is_admin = true`; password matches; account is active.
- **Expected Behavior:** System validates credentials, issues an authentication session token, displays a success toast notification "You have successfully logged in. Redirecting...".
- **Expected Outcome:** User is redirected to `/` (Admin Dashboard) after a 1.5-second delay.

**AC-002: Rejection of regular member credentials in Admin Panel**
- **Scenario:** Non-admin gym member attempts to log into the Admin Panel.
- **Conditions:** Valid email and password for a user whose `is_admin` attribute is `false`.
- **Expected Behavior:** Backend rejects login with HTTP 401 and message "you are not allowed to access this page".
- **Expected Outcome:** Error toast "Unable to logged you in" or permission denied is displayed. User remains on `/login` with no token stored.

**AC-003: Validation of non-existent email address**
- **Scenario:** User enters an email that does not exist in the system.
- **Conditions:** Email format is syntactically valid but unregistered in `users`.
- **Expected Behavior:** Backend validation identifies the missing record.
- **Expected Outcome:** Authentication fails. Error notification is displayed. Form remains editable.

**AC-004: Validation of incorrect password**
- **Scenario:** Existing admin submits an incorrect password.
- **Conditions:** Valid admin email; incorrect password value provided.
- **Expected Behavior:** Credential hash comparison fails.
- **Expected Outcome:** Rejection with authentication failure notification. No session token generated.

**AC-005: Validation of missing mandatory inputs**
- **Scenario:** User attempts submission with empty email or empty password.
- **Conditions:** Either or both fields left blank.
- **Expected Behavior:** HTML5 and client-side form validation blocks the request.
- **Expected Outcome:** Focus is placed on the required empty field. No network request is dispatched.

**AC-006: Boundary validation on password length**
- **Scenario:** Password length below 5 characters or exceeding 20 characters.
- **Conditions:** Password contains 4 characters (below min) or 21 characters (above max).
- **Expected Behavior:** System rejects the submission with validation errors specifying length constraints.
- **Expected Outcome:** Submission is rejected; no authentication token is granted.

#### Test Data Requirements
- Valid Admin: `admin@admin.com` / `password` (`is_admin = true`).
- Regular Member: `member@example.com` / `password` (`is_admin = false`).
- Non-existent Email: `ghost_user_99@test.com`.
- Short Password: `pass` (4 chars).
- Long Password: `123456789012345678901` (21 chars).

#### Dependencies & Isolation
- Pre-seeded users in SQLite/MySQL database.
- Can be tested independently by issuing requests with direct user fixture states.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable using pre-seeded admin/member credentials without executing prior workflows. |
| Negotiable | Yes | Focuses on observable login outcomes, toast messages, and authorization barriers. |
| Valuable | Yes | Protects back-office operations from unauthorized entry. |
| Estimable | Yes | Clearly scoped to credential validation and role-based redirect. |
| Small | Yes | Covers exclusively the admin sign-in mechanism. |
| Testable | Yes | Directly verifiable via valid/invalid credential scenarios. |

---

### US-002: Member Self-Registration
- **Module:** Authentication & Member Onboarding
- **Priority:** High
- **User Role:** Prospective Gym Member (Public)
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Prospective Gym Member**,  
I want to **register an account through the Member PWA by providing my name, email, and password**,  
So that **I can create a personal membership profile and access member features.**

#### Business Context
Self-registration allows gym clients to onboard digitally from their smartphones without requiring front-desk staff manual entry.

#### Scope
- **In Scope:** Registration form on Member PWA (`http://127.0.0.1:5174/register`), validation of unique email, password confirmation matching, creation of `User` entity (`is_admin = false`, `status = active`), auto-generation of unique `account_number`, automatic creation of associated blank `Profile` record.
- **Out of Scope:** Admin account creation, member subscription checkout.

#### Preconditions
- Member PWA application is accessible at `/register`.

#### Detailed Functional Description
The visitor opens `/register`. The form prompts for Name, Email Address, Password, and Confirm Password. Upon submission, the system verifies mandatory completion, checks email uniqueness, and verifies that Password equals Confirm Password. On success, a new member record is persisted with an automatically generated numeric account number and linked profile record, and the user is redirected to `/login` with a confirmation toast.

#### Business Rules
- **BR-004 (Unique Email Identity):** Every member account must have a unique email address across the entire application.
- **BR-005 (Default Member Role):** All self-registered accounts are automatically assigned `is_admin = false` and status `active`.
- **BR-006 (Account Number Generation):** A unique 10-digit gym account number is automatically generated for every new member upon creation.
- **BR-007 (Password Match & Complexity):** Password and Password Confirmation must match identically and be between 5 and 20 characters in length.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Name | Full name of the applicant | Yes | String | 1-255 characters | Required | `Jane Doe` |
| Email | Contact and login email | Yes | String | Standard email format | Required, Unique in `users` | `jane.doe@example.com` |
| Password | Member secret passphrase | Yes | String | Plain text string | Required, Min 5, Max 20 chars | `secret123` |
| Confirm Password | Re-entered password | Yes | String | Plain text string | Required, Must match `Password` | `secret123` |

#### Acceptance Criteria

**AC-007: Successful member self-registration**
- **Scenario:** Visitor submits complete, valid, and unique registration details.
- **Conditions:** Email is not in use; password meets length requirements; confirm password matches.
- **Expected Behavior:** System creates a new `users` record with `is_admin = false`, generates a unique `account_number`, creates an associated `profiles` record, and returns HTTP 201.
- **Expected Outcome:** User is redirected to `/login` with success confirmation toast "Registration successful. Please log in.".

**AC-008: Prevent registration with duplicate email address**
- **Scenario:** Visitor registers using an email already belonging to another user.
- **Conditions:** Email matches an existing active or inactive account.
- **Expected Behavior:** System identifies non-unique email and rejects request with HTTP 422.
- **Expected Outcome:** Registration fails; validation message "The email has already been taken." is presented. No record is created.

**AC-009: Prevent registration when password confirmation mismatches**
- **Scenario:** Visitor inputs differing strings in Password and Confirm Password.
- **Conditions:** Password = `password123`, Confirm Password = `password456`.
- **Expected Behavior:** Validation rule `same:password` triggers rejection.
- **Expected Outcome:** Registration is blocked; validation message "The password confirmation does not match." is displayed.

**AC-010: Validation of password boundary limits**
- **Scenario:** Password length submitted is 4 characters or 21 characters.
- **Conditions:** Values outside 5–20 character limits.
- **Expected Behavior:** Input validation rejects submission.
- **Expected Outcome:** Form highlights password field with minimum/maximum character limit violations.

**AC-011: Verification of automatic account number creation**
- **Scenario:** Inspecting newly created member profile details.
- **Conditions:** Registration completed via AC-007.
- **Expected Behavior:** Backend lifecycle hook executes `generateAccountNumber`.
- **Expected Outcome:** Member record possesses a non-null, 10-digit numerical `account_number`.

#### Test Data Requirements
- Valid Data: Name: `Marcus Vance`, Email: `marcus.v@testgym.com`, Password: `gymPass1`, Confirm: `gymPass1`.
- Existing Duplicate Email: `admin@admin.com`.
- Mismatched Passwords: `pass123` vs `pass321`.

#### Dependencies & Isolation
- Independent of existing gym data; only requires database connectivity.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Can be verified without existing member records. |
| Negotiable | Yes | Defines input rules and business outcomes without dictating frontend styling. |
| Valuable | Yes | Enables client onboarding into the gym database. |
| Estimable | Yes | Standard user registration boundary scope. |
| Small | Yes | Covers only registration processing. |
| Testable | Yes | Confirmed through form submissions and database state validation. |

---

### US-003: Member PWA Authentication & Session Management
- **Module:** Authentication & Member PWA
- **Priority:** High
- **User Role:** Gym Member
- **Story Type:** Security / Functional

#### User Story Statement
As a **Gym Member**,  
I want to **log into the Member PWA with my credentials and log out when finished**,  
So that **I can securely view my gym subscriptions, check-in history, and manage my personal profile.**

#### Business Context
Gym members use personal smartphones to access their digital club pass. Secure authentication guarantees privacy of personal contact information and attendance history.

#### Scope
- **In Scope:** Member login at `/login`, credential verification, session token storage in browser `localStorage`, authorized navigation to home `/`, and logout clearing credentials.
- **Out of Scope:** Admin portal login (handled in `US-001`).

#### Preconditions
- A registered member user exists with `is_admin = false`.

#### Detailed Functional Description
The member opens `/login` on port 5174. They provide Email and Password. Upon submitting, the system authenticates the user, stores the Sanctum bearer token in `authStore` (`localStorage`), displays a "Welcome back! Redirecting..." notification, and navigates to the Member Dashboard `/`. On any authorized screen, clicking "Sign Out" calls `/api/auth/logout`, purges local tokens, and redirects back to `/login`.

#### Business Rules
- **BR-008 (Member Access Scope):** Member PWA accepts valid user credentials regardless of `is_admin` status, but provides non-administrative views.
- **BR-009 (Session Revocation on Logout):** Logging out must invalidate the Sanctum personal access token on the server and purge local storage tokens.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Email | Member email | Yes | String | Standard email format | Required, Must exist | `member@example.com` |
| Password | Member password | Yes | String | Plain text string | Required, Min 5, Max 20 chars | `password` |

#### Acceptance Criteria

**AC-012: Successful member authentication**
- **Scenario:** Registered gym member logs in with correct credentials.
- **Conditions:** User account exists; password matches.
- **Expected Behavior:** System validates user, stores Bearer token in local storage, updates `authStore`.
- **Expected Outcome:** User is routed to Member Dashboard `/` showing their name and quick action cards.

**AC-013: Member sign out**
- **Scenario:** Authenticated member clicks "Sign Out" in Profile view.
- **Conditions:** Member is actively logged in with valid token.
- **Expected Behavior:** System executes logout endpoint, purges token from `localStorage`, and resets application state.
- **Expected Outcome:** User is redirected to `/login`. Accessing protected routes (`/profile`, `/subscription`) redirects back to `/login`.

**AC-014: Unauthenticated route guarding**
- **Scenario:** Anonymous user attempts to navigate directly to `/subscription` or `/profile`.
- **Conditions:** No auth token present in browser storage.
- **Expected Behavior:** Route guard middleware intercepts the request.
- **Expected Outcome:** Browser is automatically redirected to `/login`.

#### Test Data Requirements
- Valid Member: `member@example.com` / `password`.
- Inactive / Unregistered Member: `unregistered@example.com`.

#### Dependencies & Isolation
- Requires an existing member record created via seeders or `US-002`.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable using existing seeded member accounts. |
| Negotiable | Yes | Specifies observable session transitions. |
| Valuable | Yes | Core access mechanism for members. |
| Estimable | Yes | Standard login/logout verification. |
| Small | Yes | Covers member session lifecycle only. |
| Testable | Yes | Verifiable via browser storage inspection and protected page navigation. |

---

### US-004: Password Reset Request (Forgot Password)
- **Module:** Authentication & Password Recovery
- **Priority:** Medium
- **User Role:** All Users (Admin and Member)
- **Story Type:** Functional / Security

#### User Story Statement
As a **Registered User**,  
I want to **request a password reset link by submitting my registered email**,  
So that **I can regain access to my account if I forget my password.**

#### Business Context
Reduces administrative overhead by enabling automated self-service password recovery for members and staff who lose their credentials.

#### Scope
- **In Scope:** Forgot password page on Admin (`/forgot`) and Member PWA (`/forgot-password`), email submission validation, password reset token generation in database table `password_resets`.
- **Out of Scope:** Completing password reset with token (handled in `US-005`).

#### Preconditions
- The user must have a previously registered email address.

#### Detailed Functional Description
The user navigates to the forgot password screen, enters their email, and clicks "Send Reset Link" / "Submit". The system checks whether the email exists. If valid, a password reset token is created and a reset notification is triggered. A success confirmation toast informs the user that instructions have been dispatched. If the email format is invalid or missing, appropriate validation errors appear.

#### Business Rules
- **BR-010 (Registered Email Requirement):** Password reset requests require a syntactically valid email address. If the email does not exist in the database, the system must not disclose account non-existence for security reasons or must return a generic handler response.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Email | Account email address | Yes | String | Email format | Required, Valid email syntax | `john@example.com` |

#### Acceptance Criteria

**AC-015: Successful password reset request**
- **Scenario:** User submits a valid registered email address.
- **Conditions:** Email exists in `users` table.
- **Expected Behavior:** System accepts request, inserts a hashed token record into `password_resets`, and returns HTTP 200.
- **Expected Outcome:** UI displays success toast "Password reset instruction has been sent. Redirecting...".

**AC-016: Rejection of malformed email input**
- **Scenario:** User submits text that does not conform to email structure.
- **Conditions:** Input is `not-an-email`.
- **Expected Behavior:** Form validation blocks submission or API returns 422 Unprocessable Entity.
- **Expected Outcome:** Form highlights the invalid format error. No token is generated.

**AC-017: Handling empty email input**
- **Scenario:** User clicks submit without entering an email.
- **Conditions:** Email field is empty string.
- **Expected Behavior:** Client-side HTML5 validation blocks the submission.
- **Expected Outcome:** Browser displays "Please fill out this field".

#### Test Data Requirements
- Existing Email: `admin@admin.com`.
- Malformed Email: `invalid_email_format@@.com`.

#### Dependencies & Isolation
- Independent of other modules; requires only one existing user record.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable independently from reset execution. |
| Negotiable | Yes | Describes reset dispatch behavior. |
| Valuable | Yes | Critical account recovery feature. |
| Estimable | Yes | Simple single-input request flow. |
| Small | Yes | Covers request dispatch only. |
| Testable | Yes | Verifiable via UI notifications and `password_resets` database records. |

---

### US-005: Password Reset Execution
- **Module:** Authentication & Password Recovery
- **Priority:** Medium
- **User Role:** Registered User with Reset Token
- **Story Type:** Functional / Security

#### User Story Statement
As a **User with a valid password reset token**,  
I want to **submit a new password along with my token and email**,  
So that **I can update my account credentials and log back into the system.**

#### Business Context
Finalizes the self-service credential recovery workflow after an account owner receives a reset token.

#### Scope
- **In Scope:** Password reset form (`/reset`), token validation, minimum password length (8 characters), password confirmation matching, updating password hash in `users`, deleting consumed reset token.
- **Out of Scope:** Requesting the token (handled in `US-004`).

#### Preconditions
- A valid reset token exists in `password_resets` for the target email.

#### Detailed Functional Description
User loads `/reset?token=XYZ&email=user@example.com`. User inputs a new password and re-enters it in password confirmation. Clicking "Reset Password" verifies that the token matches the stored database token, verifies password complexity (minimum 8 characters, confirmed), updates the password, purges the token, and displays a success toast before redirecting to `/login`.

#### Business Rules
- **BR-011 (Password Reset Complexity):** New passwords set via the reset workflow must be a minimum of 8 characters in length and match confirmation.
- **BR-012 (Single-Use Token):** Once a password reset token is used successfully, it is immediately invalidated and deleted from the database.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Token | Secret security reset token | Yes | String | Alphanumeric token string | Required, Must match DB record | `abcdef123456...` |
| Email | Associated user email | Yes | String | Standard email format | Required, Valid email | `admin@admin.com` |
| Password | New replacement password | Yes | String | Plain text string | Required, Min 8 characters | `NewSecret890!` |
| Confirm Password | Re-entered new password | Yes | String | Plain text string | Required, Must match Password | `NewSecret890!` |

#### Acceptance Criteria

**AC-018: Successful password reset execution**
- **Scenario:** User provides a valid token, correct email, and matching 8+ character password.
- **Conditions:** Token matches record in `password_resets`; password equals confirmation.
- **Expected Behavior:** System updates password hash in `users`, deletes token from `password_resets`, returns HTTP 200.
- **Expected Outcome:** Success notification "You have successfully reset your password. Redirecting..." is displayed; user is routed to `/login`. User can now authenticate with the new password.

**AC-019: Rejection of mismatched new password confirmation**
- **Scenario:** User enters differing passwords in new password and confirm fields.
- **Conditions:** Password = `NewPassword88`, Confirm Password = `DifferentPassword99`.
- **Expected Behavior:** Form validation rejects submission with confirmation error.
- **Expected Outcome:** Password is not changed; user remains on form.

**AC-020: Enforce minimum 8-character length constraint**
- **Scenario:** User enters a 7-character password.
- **Conditions:** Password length < 8 characters.
- **Expected Behavior:** Validation error triggers: password must be at least 8 characters.
- **Expected Outcome:** Password is not updated; validation message displayed.

**AC-021: Rejection of invalid or expired reset token**
- **Scenario:** User submits an expired, modified, or bogus token.
- **Conditions:** Token does not exist in `password_resets`.
- **Expected Behavior:** Backend rejects request with error "unable to reset password" (HTTP 400).
- **Expected Outcome:** Error toast displayed; password remains unchanged.

#### Test Data Requirements
- Pre-inserted reset token in `password_resets` for `admin@admin.com`.
- New Password: `newPassword123` (confirmed).
- Short Password: `short7` (7 characters).

#### Dependencies & Isolation
- Can be tested by manually inserting a known token hash into `password_resets`.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable using direct token test fixtures. |
| Negotiable | Yes | Focuses on credential update and token consumption rules. |
| Valuable | Yes | Completes account recovery. |
| Estimable | Yes | Clear boundary conditions (length, match, token validity). |
| Small | Yes | Covers password reset execution only. |
| Testable | Yes | Verifiable through subsequent login attempts with the new password. |

---

### US-006: Executive KPI Dashboard & Real-Time Metrics
- **Module:** Dashboard & Reporting
- **Priority:** Medium
- **User Role:** Gym Administrator
- **Story Type:** Functional / Informational

#### User Story Statement
As a **Gym Administrator**,  
I want to **view executive summary metric cards on the Admin Dashboard**,  
So that **I can immediately monitor total active subscriptions, services, packages, and registered members.**

#### Business Context
Provides gym management with high-level operational visibility upon entering the system to evaluate club scale and membership engagement.

#### Scope
- **In Scope:** Dashboard page at `/`, four real-time metric cards (Total Subscriptions, Total Services, Total Packages, Total Members), interactive click-through links to respective management modules.
- **Out of Scope:** Custom date range reporting, financial revenue graphing.

#### Preconditions
- Administrator is logged into the Admin Panel.

#### Detailed Functional Description
Upon loading `/`, the dashboard executes parallel metric requests for subscriptions, services, packages, and members. The values represent current counts of active records in the database. Each card displays an icon, title, count value, and links directly to its respective management index.

#### Business Rules
- **BR-013 (Active Record Aggregation):** Metric counts for Services, Packages, and Subscriptions must only aggregate records whose `status = 'active'`. Inactive, expired, or deleted records must not be counted.
- **BR-014 (Member Count Definition):** Total Members KPI must count all non-admin users (`is_admin = false`).

#### Field Specifications
Read-only display cards:
| Metric Card | Metric Description | Calculated Expression | Click Destination |
|---|---|---|---|
| Total Subscription | Count of active member subscriptions | `COUNT(subscriptions WHERE status = 'active')` | `/subscriptions` |
| Total Services | Count of active gym services | `COUNT(services WHERE status = 'active')` | `/services` |
| Total Packages | Count of active membership bundles | `COUNT(packages WHERE status = 'active')` | `/packages` |
| Total Members | Total count of gym clients | `COUNT(users WHERE is_admin = false)` | `/members` |

#### Acceptance Criteria

**AC-022: Display accurate real-time KPI counts**
- **Scenario:** Administrator accesses the Dashboard.
- **Conditions:** Database contains known numbers of active packages, services, subscriptions, and members.
- **Expected Behavior:** System retrieves and renders metric counts matching the database active state.
- **Expected Outcome:** Four cards render exact numeric values.

**AC-023: Exclusion of inactive and deleted records from KPIs**
- **Scenario:** Changing a service or package status from `active` to `inactive`.
- **Conditions:** One active service is updated to `inactive`.
- **Expected Behavior:** Metric cache expires/refreshes.
- **Expected Outcome:** Total Services count decreases by 1.

**AC-024: Navigation click-through on metric cards**
- **Scenario:** Administrator clicks on the "Total Members" metric card.
- **Conditions:** Dashboard loaded.
- **Expected Behavior:** Router navigates to `/members`.
- **Expected Outcome:** The Members directory table is displayed.

#### Test Data Requirements
- 2 Active Services, 1 Inactive Service.
- 3 Active Packages, 1 Inactive Package.
- 5 Non-admin Members, 1 Admin.

#### Dependencies & Isolation
- Can be tested by asserting card counts against direct database query counts.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Reads aggregated states; does not mutate other workflows. |
| Negotiable | Yes | Describes data presentation and filtering rules. |
| Valuable | Yes | Immediate club overview for managers. |
| Estimable | Yes | Read-only metric verification. |
| Small | Yes | Covers dashboard KPI statistics only. |
| Testable | Yes | Verifiable by comparing displayed counts against database records. |

---

### US-007: Front-Desk Member Check-in/Check-out Kiosk
- **Module:** Front-Desk Operations & Attendance
- **Priority:** High
- **User Role:** Front-Desk Staff / Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Front-Desk Staff Member**,  
I want to **search for a member on the Dashboard and log their attendance check-in or check-out**,  
So that **I can verify their membership status and record their gym visit.**

#### Business Context
Front-desk staff greet arriving members, verify that they hold an active subscription, and log their entry/exit for safety, capacity monitoring, and audit trails.

#### Scope
- **In Scope:** "Member Login" widget on Dashboard (`/`), member search dropdown, display of member profile card, status badge, active subscriptions list, assigned branches, check-in/check-out selector, activity audit log creation.
- **Out of Scope:** Self check-in barcode scanner hardware integration.

#### Preconditions
- Administrator is logged in and on the Dashboard.
- At least one member exists in the database.

#### Detailed Functional Description
The staff member selects a gym member via the "Search Member" auto-complete dropdown. The widget displays the member's avatar, name, status, email, active subscriptions, and assigned branches. The staff member selects the attendance action ("Member Login" for check-in or "Member Logout" for check-out) from a dropdown and clicks "Log Status". The system records an attendance activity in `activities`, displays a success toast (e.g. `User 5 : Jane Doe has logged in`), and immediately refreshes the adjacent "Latest Attendance" table.

#### Business Rules
- **BR-015 (Attendance Log Structure):** An attendance activity must record `entity_id = user.id`, `type = 'attendance'`, and a structured description formatted as `User {id} : {name} has logged in` or `User {id} : {name} has logged out`.
- **BR-016 (Visibility of Member Subscriptions):** Selecting a member must display all subscriptions belonging to that member so staff can visually verify expiry dates.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Search Member | Member search selection | Yes | Selection | Existing user record | Required to display member details | `Jane Doe` |
| Type | Attendance movement type | Yes | Dropdown | `login` (Check-in), `logout` (Check-out) | Required | `Member Login` |

#### Acceptance Criteria

**AC-025: Member search and information preview**
- **Scenario:** Staff selects a member in the search box.
- **Conditions:** Member has an active subscription and assigned branches.
- **Expected Behavior:** System fetches subscriptions via `/subscriptions?user_id={id}` and branches via `/users/{id}/branches`.
- **Expected Outcome:** Card displays Member avatar, Name, Status badge, Subscriptions, and Branches.

**AC-026: Logging a member check-in (Login)**
- **Scenario:** Staff logs an arriving member.
- **Conditions:** Member selected; Type set to "Member Login".
- **Expected Behavior:** System posts to `/activities` with `type: 'attendance'`, `entity_id: user.id`, and formatted description.
- **Expected Outcome:** Success toast `User {id} : {name} has logged in` appears. The entry immediately shows at the top of the Latest Attendance table.

**AC-027: Logging a member check-out (Logout)**
- **Scenario:** Staff logs a departing member.
- **Conditions:** Member selected; Type set to "Member Logout".
- **Expected Behavior:** System posts to `/activities` with description `User {id} : {name} has logged out`.
- **Expected Outcome:** Success toast confirms departure. Latest Attendance table updates.

**AC-028: Display message when member has no active subscription**
- **Scenario:** Staff selects a member who has never subscribed.
- **Conditions:** Member has 0 subscriptions in database.
- **Expected Behavior:** System displays "This member does not have any subscriptions".
- **Expected Outcome:** Staff is alerted visually that the client has no valid subscription.

#### Test Data Requirements
- Member with active subscription: `Jane Doe` (Package: Gold Annual).
- Member with no subscription: `New Client`.

#### Dependencies & Isolation
- Can be tested independently by pre-selecting any existing member.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable with any seeded member account. |
| Negotiable | Yes | Focuses on attendance logging outcomes and verification displays. |
| Valuable | Yes | Core front-desk operational workflow. |
| Estimable | Yes | Clear interactive search-and-log workflow. |
| Small | Yes | Focused exclusively on front-desk kiosk check-in/out. |
| Testable | Yes | Directly verified by inspecting toast message and attendance table refresh. |

---

### US-008: Billing Cycles Listing, Search & Pagination
- **Module:** Billing Cycles Configuration
- **Priority:** Medium
- **User Role:** Gym Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **view, filter, and paginate through all configured billing cycles**,  
So that **I can review existing membership duration standards.**

#### Business Context
Billing cycles define duration units (e.g. Daily = 1 day, Monthly = 30 days, Annual = 365 days). Admins must be able to audit and manage these options.

#### Scope
- **In Scope:** Cycles table at `/cycles`, columns (ID, Name, Number of Days, Status, Description, Actions), name search filter, status filter, pagination controls.
- **Out of Scope:** Adding new cycles (handled in `US-009`).

#### Preconditions
- Administrator is logged in.

#### Detailed Functional Description
Admin navigates to `/cycles`. The system displays a table of all configured cycles. Admin can type in the search bar to filter by name (`filter[name]`), filter by status (`filter[status]`), change page sizes (5, 10, 15, 20, 40, 100), and switch pages.

#### Business Rules
- **BR-017 (Cycle Status Types):** Permitted cycle statuses are `active`, `inactive`, and `deleted`.
- **BR-018 (Pagination Sizing):** Default page size is 15 items per page.

#### Field Specifications
Search and filter controls:
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Search | Search cycle by name | No | String | Partial string match | Max 255 chars | `Month` |
| Status | Filter by active status | No | Dropdown | `active`, `inactive`, `deleted` | Must match status enum | `active` |

#### Acceptance Criteria

**AC-029: Render billing cycles directory with required attributes**
- **Scenario:** Admin opens `/cycles`.
- **Conditions:** Cycles exist in the database.
- **Expected Behavior:** Table renders columns: ID, Name, Number of Days, Status badge, Description, and Action buttons (Edit/Delete).
- **Expected Outcome:** All records are listed with correct duration days and status badges.

**AC-030: Filter cycles by name query**
- **Scenario:** Admin enters a partial keyword in the search bar.
- **Conditions:** Cycles "Monthly", "Annual", and "Daily" exist; query is "Month".
- **Expected Behavior:** System executes query with partial name matching.
- **Expected Outcome:** Table updates to show only "Monthly".

**AC-031: Change pagination page size**
- **Scenario:** Admin changes page size selector from 15 to 5.
- **Conditions:** More than 5 cycle records exist.
- **Expected Behavior:** System recalculates pages and renders maximum 5 items.
- **Expected Outcome:** Table truncates to 5 rows and updates pagination counter.

#### Test Data Requirements
- Cycle 1: Name "Daily", 1 day, Status "active".
- Cycle 2: Name "Monthly", 30 days, Status "active".
- Cycle 3: Name "Annual", 365 days, Status "inactive".

#### Dependencies & Isolation
- Can be tested using seed data.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Read-only listing operation. |
| Negotiable | Yes | Specifies filtering and pagination outcomes. |
| Valuable | Yes | Allows oversight of billing cycle catalog. |
| Estimable | Yes | Standard list/search/paginate story. |
| Small | Yes | Covers listing and searching cycles only. |
| Testable | Yes | Verified via query assertions and table row counts. |

---

### US-009: Create, Edit & Delete Billing Cycles
- **Module:** Billing Cycles Configuration
- **Priority:** Medium
- **User Role:** Gym Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **create, update, and delete billing cycle definitions**,  
So that **the gym can define new membership durations or deprecate old ones.**

#### Business Context
When the gym launches new promotional durations (e.g. 90-day Summer Pass), staff must configure corresponding billing cycles.

#### Scope
- **In Scope:** Create form at `/cycles/new`, edit form at `/cycles/[id]`, delete action, field validation (name, num_days > 0, status, description), soft deletion.
- **Out of Scope:** Linking cycles to packages (handled in `US-012`).

#### Preconditions
- Administrator is logged in.

#### Detailed Functional Description
Admin clicks "Create Cycle" (`/cycles/new`). Admin fills in Name, Number of Days, Status (`active` or `inactive`), and Description. Clicking "Submit" posts data to `/cycles`. On success, a toast "Successfully created" appears and user returns to `/cycles`. From the table, clicking "Edit" opens `/cycles/[id]`, pre-populates data, and allows updates. Clicking "Delete" prompts for confirmation, soft-deletes the cycle, and removes it from the active view.

#### Business Rules
- **BR-019 (Positive Duration Constraint):** `num_days` must be an integer strictly greater than zero (`gt:0`). Zero or negative days are rejected.
- **BR-020 (Mandatory Description):** Description is mandatory and limited to 255 characters.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Name | Display name of the cycle | Yes | String | 1-255 characters | Required, Max 255 | `Quarterly` |
| Number of Days | Duration in calendar days | Yes | Integer | Integer > 0 | Required, Greater than 0 | `90` |
| Status | Operational status | Yes | Dropdown | `active`, `inactive`, `deleted` | Required, Enum validation | `active` |
| Description | Summary of billing period | Yes | String | 1-255 characters | Required, Max 255 | `Every 3 months billing` |

#### Acceptance Criteria

**AC-032: Successfully create a new billing cycle**
- **Scenario:** Admin submits valid cycle parameters.
- **Conditions:** All fields completed; `num_days` = 90.
- **Expected Behavior:** System inserts record in `cycles`, returns 200/201, shows success toast.
- **Expected Outcome:** Cycle appears in the `/cycles` directory.

**AC-033: Reject zero or negative number of days**
- **Scenario:** Admin enters `0` or `-15` in Number of Days.
- **Conditions:** `num_days` <= 0.
- **Expected Behavior:** Validation error triggers: `The num days must be greater than 0.`.
- **Expected Outcome:** Record is not created; form displays validation error.

**AC-034: Update an existing billing cycle**
- **Scenario:** Admin modifies the name and days of an existing cycle.
- **Conditions:** Cycle ID exists; updated `num_days` is valid.
- **Expected Behavior:** System updates record via PUT `/cycles/{id}`, shows "Successfully updated".
- **Expected Outcome:** Updated values persist in database and display in table.

**AC-035: Soft delete a billing cycle**
- **Scenario:** Admin clicks Delete on an unneeded cycle.
- **Conditions:** Cycle exists.
- **Expected Behavior:** System sends DELETE `/cycles/{id}`. Record receives `deleted_at` timestamp.
- **Expected Outcome:** Cycle is removed from the active listing.

#### Test Data Requirements
- Valid Cycle: Name "Quarterly", Days: 90, Status: "active", Desc: "Quarterly pass".
- Invalid Cycle: Name "Bad Cycle", Days: 0, Status: "active", Desc: "Test".

#### Dependencies & Isolation
- Independent; cycles do not require prior foreign keys.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | No prerequisite entities needed. |
| Negotiable | Yes | Focuses on duration validation and persistence. |
| Valuable | Yes | Foundational time unit for packages. |
| Estimable | Yes | Standard CRUD form with numerical boundary validation. |
| Small | Yes | Covers cycle creation, modification, and deletion. |
| Testable | Yes | Directly verifiable via form submission and database checks. |

---

### US-010: Gym Services Catalog Management
- **Module:** Services Management
- **Priority:** High
- **User Role:** Gym Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **create, view, edit, and delete gym service offerings**,  
So that **I can maintain an accurate inventory of facilities and amenities provided by the club.**

#### Business Context
Gyms offer discrete amenities (e.g. "Free Weights", "Locker Room", "Swimming Pool", "Group Fitness Classes", "Sauna"). These services are bundled into membership packages.

#### Scope
- **In Scope:** Services directory at `/services`, create form at `/services/new`, edit form at `/services/[id]`, delete action, field validations (Name, Status, Description), search filtering.
- **Out of Scope:** Assigning services to packages (handled in `US-012`).

#### Preconditions
- Administrator is logged in.

#### Detailed Functional Description
Admin navigates to `/services`. Admin can view all services, search by name, or click "Create Service". The create form requires Name, Status (`active`/`inactive`), and Description. Submitting saves the service. Editing an existing service allows updating descriptions or disabling amenities. Deleting a service removes it from the active offerings.

#### Business Rules
- **BR-021 (Service Status Values):** Valid statuses are `active`, `inactive`, and `deleted`.
- **BR-022 (Required Fields):** Name and Description are required and limited to 255 characters.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Name | Amenity or service name | Yes | String | 1-255 characters | Required, Max 255 | `Olympic Swimming Pool` |
| Status | Operational status | Yes | Dropdown | `active`, `inactive`, `deleted` | Required | `active` |
| Description | Details of what the service includes | Yes | String | 1-255 characters | Required, Max 255 | `Access to 50m Olympic heated pool` |

#### Acceptance Criteria

**AC-036: Successfully create a gym service**
- **Scenario:** Admin enters valid service details.
- **Conditions:** Name and description populated; status selected.
- **Expected Behavior:** Service inserted into `services` table. Success toast displayed.
- **Expected Outcome:** Service appears in `/services` list.

**AC-037: Validation of missing mandatory service name**
- **Scenario:** Admin leaves Name blank.
- **Conditions:** Name = empty string.
- **Expected Behavior:** Form validation prevents submission.
- **Expected Outcome:** Error indicates Name is required. Record is not created.

**AC-038: Update service details**
- **Scenario:** Admin updates description and toggles status to `inactive`.
- **Conditions:** Service exists.
- **Expected Behavior:** PUT `/services/{id}` updates database fields.
- **Expected Outcome:** Table shows updated description and Inactive status badge.

**AC-039: Delete gym service**
- **Scenario:** Admin deletes an obsolete service.
- **Conditions:** Service exists.
- **Expected Behavior:** System deletes service record.
- **Expected Outcome:** Service disappears from active directory table.

#### Test Data Requirements
- Valid Service: Name: "Sauna & Steam Bath", Status: "active", Desc: "Access to dry sauna and steam rooms".

#### Dependencies & Isolation
- Independent; services have no foreign key dependencies.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Can be verified without existing packages or members. |
| Negotiable | Yes | Outlines service catalog specifications. |
| Valuable | Yes | Defines club amenities. |
| Estimable | Yes | Standard CRUD form. |
| Small | Yes | Covers gym service entity operations only. |
| Testable | Yes | Directly verifiable through catalog management actions. |

---

### US-011: Gym Branch Facilities Management
- **Module:** Branch Facilities
- **Priority:** High
- **User Role:** Gym Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **manage gym branch facility locations and their operational status**,  
So that **the business can operate multiple clubs and assign members to specific locations.**

#### Business Context
Multi-location gym chains need to track individual physical club branches, their contact details, and whether they are active or undergoing renovation.

#### Scope
- **In Scope:** Branch directory at `/branches`, create form at `/branches/new`, edit form at `/branches/[id]`, delete action, validation rules (Name, Status, Description), search filtering.
- **Out of Scope:** Assigning members to branches (handled in `US-017`), member view of branches (handled in `US-026`).

#### Preconditions
- Administrator is logged in.

#### Detailed Functional Description
Admin navigates to `/branches`. The system lists all branches with their ID, Name, Description, Status, and Actions. Admin can add new branches by submitting Name, Status, and Description, or edit existing branch records.

#### Business Rules
- **BR-023 (Branch Status Rules):** Branches must have a status of `active`, `inactive`, or `deleted`.
- **BR-024 (Branch Mandatory Attributes):** Name and Description are required text fields (maximum 255 characters).

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Name | Branch / Location name | Yes | String | 1-255 characters | Required, Max 255 | `Downtown Fitness Hub` |
| Status | Operational status | Yes | Dropdown | `active`, `inactive`, `deleted` | Required | `active` |
| Description | Location description / address summary | Yes | String | 1-255 characters | Required, Max 255 | `Corner 5th & Main, Level 2` |

#### Acceptance Criteria

**AC-040: Successfully create a new branch**
- **Scenario:** Admin enters valid branch parameters.
- **Conditions:** Name, Status, and Description are valid.
- **Expected Behavior:** System creates new record in `branches`. Success toast displayed.
- **Expected Outcome:** Branch appears in the administrative branches table.

**AC-041: Enforce mandatory fields on branch creation**
- **Scenario:** Admin submits form with empty Description.
- **Conditions:** Description omitted.
- **Expected Behavior:** Validation error triggers: `The description field is required.`.
- **Expected Outcome:** Submission rejected. No record added.

**AC-042: Modify existing branch information**
- **Scenario:** Admin changes branch name and sets status to `inactive`.
- **Conditions:** Target branch exists.
- **Expected Behavior:** System updates branch record via PUT `/branches/{id}`.
- **Expected Outcome:** Table updates immediately with new branch name and Inactive badge.

**AC-043: Soft delete branch facility**
- **Scenario:** Admin clicks Delete on a branch.
- **Conditions:** Branch exists.
- **Expected Behavior:** DELETE `/branches/{id}` is executed.
- **Expected Outcome:** Branch is soft-deleted and removed from the active listing.

#### Test Data Requirements
- Valid Branch: Name: "Uptown Express", Status: "active", Desc: "24/7 express fitness centre".

#### Dependencies & Isolation
- Independent; does not depend on other tables.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Can be tested with zero dependencies. |
| Negotiable | Yes | Focuses on branch maintenance behavior. |
| Valuable | Yes | Supports multi-location gym operations. |
| Estimable | Yes | Standard entity management. |
| Small | Yes | Covers branch facility lifecycle only. |
| Testable | Yes | Verifiable via UI table inspection and persistence checks. |

---

### US-012: Membership Packages Catalog & Service Bundling
- **Module:** Membership Packages
- **Priority:** High
- **User Role:** Gym Administrator
- **Story Type:** Functional / Business Logic

#### User Story Statement
As a **Gym Administrator**,  
I want to **configure membership packages by combining a billing cycle, pricing amount, and multiple included gym services**,  
So that **members can purchase bundled gym access plans.**

#### Business Context
Packages are the core commercial products sold by the gym. A package defines what a member pays (Amount), how long it lasts (Cycle), and what facilities they are entitled to access (Services).

#### Scope
- **In Scope:** Package directory at `/packages`, create form at `/packages/new`, edit form at `/packages/[id]`, selecting a Cycle from dropdown, multi-selecting Services, setting Amount, Status, and Name, package deletion.
- **Out of Scope:** Member subscription purchasing (handled in `US-019`).

#### Preconditions
- At least one active Billing Cycle exists.
- At least one active Gym Service exists.

#### Detailed Functional Description
Admin navigates to `/packages/new`. Form fields include: Package Name, Billing Cycle (dropdown populated from `cycles`), Amount (decimal currency value), Status (`active`/`inactive`), and Services (multi-select checkboxes populated from `services`). Upon saving, the system creates the `Package` record and attaches selected services via the pivot table `package_services`. In the edit view (`/packages/[id]`), the admin can update pricing, alter the billing cycle, or synchronize service inclusions.

#### Business Rules
- **BR-025 (Valid Cycle Dependency):** Every package must link to an existing `cycle_id`.
- **BR-026 (Service Inclusion Validation):** Every selected service must exist in `services`. A package can include zero or multiple services.
- **BR-027 (Numerical Amount Requirement):** Package Amount must be a valid numerical value representing price.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Name | Commercial package title | Yes | String | 1-255 characters | Required, Max 255 | `Gold Annual All-Access` |
| Cycle | Duration cycle selection | Yes | Dropdown | Existing cycle ID | Required, Must exist in `cycles` | `Annual (365 days)` |
| Amount | Subscription price | Yes | Number / Decimal | Positive numeric value | Required, Numeric | `599.99` |
| Status | Package status | Yes | Dropdown | `active`, `inactive`, `deleted` | Required | `active` |
| Services | Included gym amenities | Yes | Multi-select / Checkbox | Array of service IDs | Each item must exist in `services` | `[Pool, Weights, Sauna]` |

#### Acceptance Criteria

**AC-044: Successfully create a bundled membership package**
- **Scenario:** Admin creates a package with valid name, cycle, price, and two bundled services.
- **Conditions:** Valid Cycle selected; Amount = 120.00; Services = [Locker, Gym Floor].
- **Expected Behavior:** System inserts `packages` record, populates `package_services` pivot, returns HTTP 200.
- **Expected Outcome:** Package appears in `/packages` list showing cycle name and bundled service count.

**AC-045: Validation of missing billing cycle selection**
- **Scenario:** Admin attempts to submit package without choosing a cycle.
- **Conditions:** Cycle field left unselected.
- **Expected Behavior:** Form validation blocks submission or API returns error `The cycle id field is required.`.
- **Expected Outcome:** Submission is blocked. Error highlighted on Cycle selector.

**AC-046: Validation of non-numeric package amount**
- **Scenario:** Admin enters alphanumeric text in Amount field.
- **Conditions:** Amount = `FREE_PASS`.
- **Expected Behavior:** Input validation blocks non-numeric values.
- **Expected Outcome:** Form enforces numeric input format.

**AC-047: Synchronize bundled services on package update**
- **Scenario:** Admin edits a package and removes one service while adding another.
- **Conditions:** Package exists with services [A, B]; user changes to [B, C].
- **Expected Behavior:** Backend executes `services()->sync([B, C])`.
- **Expected Outcome:** Pivot table updates; package profile displays only [B, C].

**AC-048: Soft delete membership package**
- **Scenario:** Admin deletes a discontinued package.
- **Conditions:** Package exists.
- **Expected Behavior:** System executes DELETE `/packages/{id}`.
- **Expected Outcome:** Package is removed from active listings. Existing subscriptions referencing the package remain preserved.

#### Test Data Requirements
- Cycle: "Monthly" (ID: 1).
- Services: "Gym Floor" (ID: 1), "Sauna" (ID: 2).
- Valid Package: Name: "Silver Monthly", Cycle: 1, Amount: 49.99, Services: [1, 2].

#### Dependencies & Isolation
- Requires existing Cycle and Service records (can be seeded).

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable independently assuming baseline reference cycles and services exist. |
| Negotiable | Yes | Describes package composition rules. |
| Valuable | Yes | Directly represents the sellable inventory of the gym. |
| Estimable | Yes | Clearly scoped bundle management. |
| Small | Yes | Covers package entity definition only. |
| Testable | Yes | Verified by inspecting package records and pivot associations. |

---

### US-013: Member Account Creation by Administrator
- **Module:** Member Management (CRM)
- **Priority:** High
- **User Role:** Gym Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **manually register a new member account from the Admin Panel**,  
So that **front-desk staff can onboard walk-in clients directly.**

#### Business Context
Not all clients register via the mobile PWA. Walk-in customers sign up at the reception desk, where staff create their system account.

#### Scope
- **In Scope:** New member form at `/members/new`, fields (Name, Email, Password, Password Confirmation), validation of unique email, password matching, auto-generation of 10-digit account number, creation of associated profile record.
- **Out of Scope:** Adding subscriptions during creation (handled in `US-019`).

#### Preconditions
- Administrator is logged in.

#### Detailed Functional Description
Admin navigates to `/members` and clicks "Create Member" (`/members/new`). Form prompts for Full Name, Email, Password, and Password Confirmation. On submission, the backend service hashes the password, creates the `User` entity with `is_admin = false`, auto-generates a unique `account_number`, initializes a linked `Profile`, and redirects back to `/members` with a success toast.

#### Business Rules
- **BR-028 (Admin-Created Member Identity):** Users created via this screen are regular members (`is_admin = false`).
- **BR-029 (Password Confirmation):** Password must be 5–20 characters and must match confirmation identically.
- **BR-030 (Unique Email):** Email must not duplicate an existing user in the system.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Name | Member full legal name | Yes | String | 1-255 characters | Required | `Robert Smith` |
| Email | Member contact email | Yes | String | Valid email address | Required, Unique in `users` | `r.smith@example.com` |
| Password | Initial account password | Yes | String | Plain text string | Required, Min 5, Max 20 chars | `welcome123` |
| Password Confirmation | Re-entered password | Yes | String | Plain text string | Required, Same as password | `welcome123` |

#### Acceptance Criteria

**AC-049: Successfully create walk-in member account**
- **Scenario:** Admin enters valid client information.
- **Conditions:** Email is unique; passwords match.
- **Expected Behavior:** System creates user record with `is_admin = 0`, generates 10-digit account number, creates profile row.
- **Expected Outcome:** Success notification "Successfully created" appears. Member appears in `/members` directory.

**AC-050: Validation of duplicate email during admin onboarding**
- **Scenario:** Admin attempts to onboard a client with an existing registered email.
- **Conditions:** Email already exists in `users`.
- **Expected Behavior:** Validation error triggers: `The email has already been taken.`.
- **Expected Outcome:** Creation is blocked. Admin is alerted of the duplicate.

**AC-051: Validation of password mismatch**
- **Scenario:** Admin mistypes password confirmation.
- **Conditions:** Password != Password Confirmation.
- **Expected Behavior:** Validation error `The password confirmation does not match.` triggers.
- **Expected Outcome:** User record is not created.

#### Test Data Requirements
- Valid Data: Name: `Alice Walker`, Email: `alice.w@domain.com`, Password: `initialPass1`.
- Duplicate Email: `admin@admin.com`.

#### Dependencies & Isolation
- Independent; requires only database connectivity.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable without pre-existing business records. |
| Negotiable | Yes | Specifies account creation requirements. |
| Valuable | Yes | Supports walk-in customer onboarding. |
| Estimable | Yes | Standard user creation flow. |
| Small | Yes | Covers admin creation of member accounts. |
| Testable | Yes | Verified via members directory and database queries. |

---

### US-014: Member Profile & Demographic Management
- **Module:** Member Management (CRM)
- **Priority:** High
- **User Role:** Gym Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **view and update a member's contact details, address, newsletter status, and account status**,  
So that **the gym maintains accurate member contact records and current billing addresses.**

#### Business Context
Staff need to update member phone numbers, residential addresses, emergency contacts, or change membership standing (e.g. suspending a delinquent account).

#### Scope
- **In Scope:** Member details page at `/members/[id]`, "Member Profile" tab, editing Name, Status (`active`, `inactive`, `suspended`), Contact Number, Address, City, State, Country, Postcode, Newsletter toggle, Email display (read-only in UI).
- **Out of Scope:** Avatar image upload (handled in `US-015`), password reset (handled in `US-016`), branch assignment (handled in `US-017`).

#### Preconditions
- Administrator is logged in.
- A member record exists.

#### Detailed Functional Description
Admin navigates to `/members` and clicks a member's name, opening `/members/[id]`. The "Member Profile" tab displays personal information. Admin can change Name, Status (Active, In-Active, Suspended), Contact Number, Address, City, State, Country (via country select dropdown), Postcode, and toggle "Subscribe to Newsletter". Clicking "Submit" updates both `users` and `profiles` tables and shows a success toast.

#### Business Rules
- **BR-031 (Member Status Lifecycle):** Valid member statuses are `active`, `inactive`, and `suspended`.
- **BR-032 (Synchronized Profile Persistence):** Profile fields (address, city, state, country, postcode, newsletter) are stored in the linked `profiles` table while Name and Status update `users`.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Name | Member full name | Yes | String | 1-255 characters | Required | `Jane Doe` |
| Email | Member login email | Read-only | String | Email format | Cannot be altered in profile tab | `jane@example.com` |
| Status | Membership standing | Yes | Dropdown | `active`, `inactive`, `suspended` | Required enum | `active` |
| Contact Number | Telephone number | No | String | Max 20 characters | Max 20 chars | `+1-555-0199` |
| Address | Street address | No | String | Max 255 characters | Max 255 chars | `742 Evergreen Terrace` |
| City | City of residence | No | String | Max 100 characters | Max 100 chars | `Springfield` |
| State | State / Province | No | String | Max 100 characters | Max 100 chars | `Oregon` |
| Country | Country code | No | Dropdown | 2-character country code | ISO 3166-1 alpha-2 | `US` |
| Postcode | Postal code | No | String | Max 20 characters | Max 20 chars | `97477` |
| Newsletter | Newsletter opt-in | No | Boolean | `true` / `false` | Boolean toggle | `true` |

#### Acceptance Criteria

**AC-052: Successfully update member demographic and address profile**
- **Scenario:** Admin updates address and contact details of an existing member.
- **Conditions:** Member exists; valid string values supplied.
- **Expected Behavior:** System updates `users` and `profiles` records via PUT `/users/{id}`.
- **Expected Outcome:** Success toast "Successfully updated" is displayed. Updated values persist on refresh.

**AC-053: Update member standing status**
- **Scenario:** Admin changes member status from `active` to `suspended`.
- **Conditions:** Target member is active.
- **Expected Behavior:** Member's `status` column updates to `suspended`.
- **Expected Outcome:** Status badge on member overview card updates to "Suspended".

**AC-054: Toggle newsletter subscription state**
- **Scenario:** Admin enables the "Subscribe to Newsletter" switch.
- **Conditions:** Initial `newsletter` value is false (0).
- **Expected Behavior:** Toggle sets `newsletter = true`.
- **Expected Outcome:** Profile table records `newsletter = 1`.

#### Test Data Requirements
- Existing Member ID: 2.
- Updated Data: Contact: `555-987-6543`, Address: `100 Fitness Way`, City: `Denver`, State: `CO`, Country: `US`, Postcode: `80201`.

#### Dependencies & Isolation
- Requires one existing member account.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Operates solely on member demographic records. |
| Negotiable | Yes | Describes demographic field updates. |
| Valuable | Yes | Essential customer record maintenance. |
| Estimable | Yes | Single-entity form update scope. |
| Small | Yes | Covers demographic attributes only. |
| Testable | Yes | Verifiable via form update and database queries. |

---

### US-015: Member Profile Avatar Photo Upload
- **Module:** Member Management (CRM)
- **Priority:** Low
- **User Role:** Gym Administrator
- **Story Type:** Functional / Media Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **upload a photo avatar for a gym member**,  
So that **staff can visually identify the member at the front-desk during check-in.**

#### Business Context
Visual identification prevents gym pass sharing and fraud by displaying the member's real photo on front-desk check-in screens.

#### Scope
- **In Scope:** Avatar upload button on Member Profile (`/members/[id]`), file type validation (jpeg, png, jpg), file size limit (10MB), server-side image processing, thumbnail storage, updating user avatar URL.
- **Out of Scope:** Webcam photo capture.

#### Preconditions
- Administrator is viewing an existing member profile at `/members/[id]`.

#### Detailed Functional Description
Admin clicks "Change Avatar" in the member profile view. The file chooser opens. Admin selects an image file. The frontend transmits the file via `multipart/form-data` to `/users/{user}/avatar`. The backend validates format and file size, uses an image manager to resize the photo maintaining aspect ratio (100px dimension), stores the file in `storage/app/public`, and updates `user.avatar`. The circular avatar preview updates immediately.

#### Business Rules
- **BR-033 (Accepted Image Formats):** Only `jpeg`, `png`, and `jpg` image formats are accepted. PDF, GIF, SVG, or executable files are rejected.
- **BR-034 (Max File Size):** Image file size must not exceed 10,000 KB (10 MB).
- **BR-035 (Image Resizing):** Uploaded avatars are automatically resized to a standard 100px bounding dimension maintaining aspect ratio.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| avatar | Member photo file | Yes | Binary / File | `.jpg`, `.jpeg`, `.png` | Max 10000 KB, valid image mime | `member_photo.png` |

#### Acceptance Criteria

**AC-055: Successfully upload valid member avatar**
- **Scenario:** Admin selects a valid PNG image (under 10MB).
- **Conditions:** Member exists; file is `profile.png` (500KB).
- **Expected Behavior:** Backend processes image, stores in storage, saves URL to `users.avatar`, returns HTTP 200.
- **Expected Outcome:** Avatar preview on profile card updates immediately with the newly uploaded photo.

**AC-056: Reject unsupported file formats**
- **Scenario:** Admin selects a PDF document or text file.
- **Conditions:** File has extension `.pdf` or mime type `application/pdf`.
- **Expected Behavior:** Validation error triggers: `The avatar must be a file of type: jpeg, png, jpg.`.
- **Expected Outcome:** Upload fails; existing avatar remains untouched.

**AC-057: Reject file exceeding 10MB limit**
- **Scenario:** Admin selects an image file larger than 10MB.
- **Conditions:** File size = 12 MB.
- **Expected Behavior:** Server rejects payload with validation error `The avatar must not be greater than 10000 kilobytes.`.
- **Expected Outcome:** Upload is rejected; error toast displayed.

#### Test Data Requirements
- Valid Image: `test_avatar.jpg` (200 KB).
- Unsupported Format: `document.pdf`.
- Oversized Image: `large_image.jpg` (11 MB).

#### Dependencies & Isolation
- Requires local storage symlink (`storage/app/public`).

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Media upload operation testable on any single member. |
| Negotiable | Yes | Describes upload requirements without prescribing storage drivers. |
| Valuable | Yes | Critical for front-desk visual member identification. |
| Estimable | Yes | File upload and validation boundary scope. |
| Small | Yes | Covers avatar upload only. |
| Testable | Yes | Verifiable via UI image update and storage directory checks. |

---

### US-016: Administrative Member Password Reset
- **Module:** Member Management & Security
- **Priority:** Medium
- **User Role:** Gym Administrator
- **Story Type:** Security / Functional

#### User Story Statement
As a **Gym Administrator**,  
I want to **manually update a member's password from their profile security tab**,  
So that **I can assist members who are locked out and unable to use automated recovery.**

#### Business Context
When members cannot access their email or experience login difficulties, front-desk administrators can verify their identity in person and reset their password.

#### Scope
- **In Scope:** "Security and Privacy" tab on `/members/[id]`, Password and Password Confirmation inputs, length validation (5–20 characters), password matching, updating password hash in `users`.
- **Out of Scope:** Sending notification emails.

#### Preconditions
- Administrator is viewing an existing member profile at `/members/[id]`.

#### Detailed Functional Description
Admin selects the "Security and Privacy" subcomponent tab. Form displays Password and Password Confirmation fields. Admin inputs a temporary password for the member and confirms it. Submitting sends a PUT request to `/users/{id}` with the new password. The system verifies matching and length constraints, hashes the password via Bcrypt, updates the member record, and displays "Successfully updated".

#### Business Rules
- **BR-036 (Admin Reset Password Rules):** The new password must be between 5 and 20 characters and must match the confirmation field.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Password | New temporary password | Yes | String | 5-20 characters | Min 5, Max 20 | `tempPass2026` |
| Password Confirmation | Re-entered password | Yes | String | 5-20 characters | Must match Password | `tempPass2026` |

#### Acceptance Criteria

**AC-058: Successfully update member password**
- **Scenario:** Admin enters a valid, matching new password.
- **Conditions:** Password = `securePass1`; Confirmation = `securePass1`.
- **Expected Behavior:** System hashes and updates password on `users` record.
- **Expected Outcome:** Success toast "Successfully updated" is displayed. Member can immediately log into the Member PWA using the new password.

**AC-059: Validation of mismatched password confirmation**
- **Scenario:** Admin enters non-matching values in password confirmation.
- **Conditions:** Password != Password Confirmation.
- **Expected Behavior:** Validation error triggers: `The password confirmation does not match.`.
- **Expected Outcome:** Password is not updated.

**AC-060: Enforce length boundaries on admin password reset**
- **Scenario:** Admin enters a 4-character password.
- **Conditions:** Password length < 5 characters.
- **Expected Behavior:** Validation rule `min:5` blocks the request.
- **Expected Outcome:** Form displays length requirement error.

#### Test Data Requirements
- Existing Member ID: 2.
- New Password: `newGymPass77`.

#### Dependencies & Isolation
- Requires an existing member record.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable on any existing member without altering other records. |
| Negotiable | Yes | Focuses on credential modification outcome. |
| Valuable | Yes | Resolves customer lockouts. |
| Estimable | Yes | Standard credential update form. |
| Small | Yes | Covers administrative credential update only. |
| Testable | Yes | Directly verified by logging in with the updated password. |

---

### US-017: Multi-Branch Member Assignment
- **Module:** Member Management & Multi-Branch
- **Priority:** Medium
- **User Role:** Gym Administrator
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Administrator**,  
I want to **assign one or more gym branches to a member's profile**,  
So that **the system designates which club locations the member is authorized to access.**

#### Business Context
Gym chains with multiple clubs allow single-club, regional, or all-club access. Admins assign authorized branch access per member.

#### Scope
- **In Scope:** "Member Branches" tab on `/members/[id]`, multi-select branch dropdown, loading current branch assignments, updating `user_branch` pivot records via POST `/users/{id}/branches`.
- **Out of Scope:** Automatic branch assignment via package purchase rules.

#### Preconditions
- Multiple branches exist in the database.
- A member record exists.

#### Detailed Functional Description
Admin opens `/members/[id]` and clicks the "Member Branches" tab. The system queries GET `/users/{id}/branches` and pre-populates currently linked branches. Admin can add or remove branches from the multi-select input. Clicking "Submit" sends an array of branch IDs to POST `/users/{id}/branches`. The backend synchronizes the `user_branch` pivot table and returns a confirmation toast.

#### Business Rules
- **BR-037 (Valid Branch Reference):** Each branch ID in the submitted array must exist in `branches`.
- **BR-038 (Pivot Synchronization):** Submitting branch assignments synchronizes the pivot table: branches omitted from the submission are unlinked; newly selected branches are added.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Branches | Authorized gym branch locations | Yes | Array of Integers | IDs of existing branches | Array required, each must exist in `branches` | `[1, 3]` |

#### Acceptance Criteria

**AC-061: Successfully assign multiple branches to a member**
- **Scenario:** Admin selects two branches for a member with no prior branches.
- **Conditions:** Member exists; Branches 1 ("Downtown") and 2 ("West End") selected.
- **Expected Behavior:** System synchronizes `user_branch` pivot table with branch IDs [1, 2].
- **Expected Outcome:** Success toast "successfully updated" appears. Both branches display in member's branch list and Member PWA.

**AC-062: Remove a branch assignment from a member**
- **Scenario:** Member currently has branches [1, 2]; admin deselects Branch 2 and submits [1].
- **Conditions:** Branch 2 removed from selection.
- **Expected Behavior:** Backend syncs array [1], deleting row for Branch 2 in `user_branch`.
- **Expected Outcome:** Member profile reflects only Branch 1.

**AC-063: Clear all branch assignments**
- **Scenario:** Admin removes all selected branches and submits empty array.
- **Conditions:** All branches deselected.
- **Expected Behavior:** Validation requires array, syncs empty array or rejects empty depending on form validation.
- **Expected Outcome:** All linked branches removed or validation requires at least one branch.

#### Test Data Requirements
- Branches: Branch 1 ("Central"), Branch 2 ("North").
- Target Member ID: 3.

#### Dependencies & Isolation
- Requires at least two active branches and one member.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable using existing branches and member fixtures. |
| Negotiable | Yes | Describes branch association outcome. |
| Valuable | Yes | Governs multi-club access privileges. |
| Estimable | Yes | Multi-select association synchronization scope. |
| Small | Yes | Covers branch linking only. |
| Testable | Yes | Verifiable via pivot table inspection and member profile view. |

---

### US-018: Member Deletion & Self-Protection Rules
- **Module:** Member Management (CRM)
- **Priority:** Medium
- **User Role:** Gym Administrator
- **Story Type:** Functional / Business Rule

#### User Story Statement
As a **Gym Administrator**,  
I want to **delete a member account from the system while being prevented from deleting my own admin account**,  
So that **I can maintain clean customer records without accidentally locking myself out of the system.**

#### Business Context
GDPR and data management require removing obsolete or duplicate member records. Self-protection business rules safeguard administrators from deleting their own access.

#### Scope
- **In Scope:** Delete member action on `/members`, confirmation modal, soft deletion in `users`, protection preventing admin from deleting accounts where `is_admin = true`.
- **Out of Scope:** Permanent hard-delete / database purge.

#### Preconditions
- Administrator is logged in.
- Target non-admin member exists.

#### Detailed Functional Description
Admin navigates to `/members` and clicks "Delete" on a member row. A confirmation dialog appears. Confirming triggers DELETE `/users/{id}`. The backend inspects `user.is_admin`. If `is_admin` is false, it executes soft deletion (`deleted_at` timestamp set) and returns HTTP 204. If the user is an admin (`is_admin = true`), the request is rejected with HTTP 401 and message "you are not allowed to do this to yourself".

#### Business Rules
- **BR-039 (Admin Self-Deletion Protection):** An account with `is_admin = true` cannot be deleted through the user controller. Attempted deletion returns HTTP 401.
- **BR-040 (Soft Deletion):** Member deletion must be a soft delete, preserving historical audit trails, subscriptions, and financial records.

#### Field Specifications
| Parameter | Description | Mandatory | Data Type | Validation Rules | Example |
|---|---|---|---|---|---|
| id | User ID to delete | Yes | Integer | Must exist in `users` | `5` |

#### Acceptance Criteria

**AC-064: Successfully delete a regular gym member**
- **Scenario:** Admin deletes a non-admin member.
- **Conditions:** Target user has `is_admin = false`.
- **Expected Behavior:** System executes soft delete, populates `deleted_at`.
- **Expected Outcome:** Member is removed from `/members` table. Existing historical subscriptions remain intact in database.

**AC-065: Rejection of admin account deletion**
- **Scenario:** Admin attempts to delete an administrator user account.
- **Conditions:** Target user has `is_admin = true`.
- **Expected Behavior:** System halts deletion, returns HTTP 401 with message "you are not allowed to do this to yourself".
- **Expected Outcome:** Error notification is displayed. Admin record is not deleted.

#### Test Data Requirements
- Regular Member to delete: User ID 4 (`is_admin = false`).
- Administrator to protect: User ID 1 (`is_admin = true`).

#### Dependencies & Isolation
- Independent; can be tested on any disposable member account.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable using any non-admin member. |
| Negotiable | Yes | Specifies safety rule and outcome. |
| Valuable | Yes | Protects system availability and data cleanup. |
| Estimable | Yes | Deletion and self-protection logic. |
| Small | Yes | Covers user deletion rules only. |
| Testable | Yes | Directly verifiable via API response codes and table removal. |

---

### US-019: Issue New Member Subscription
- **Module:** Subscriptions Management
- **Priority:** High
- **User Role:** Gym Administrator
- **Story Type:** Functional / Business Logic

#### User Story Statement
As a **Gym Administrator**,  
I want to **issue a new membership subscription to a member for a chosen package and interval**,  
So that **the member receives an active gym pass with an automatically calculated expiration date.**

#### Business Context
When a client purchases gym access, staff register the subscription in the system. The platform automatically computes the membership expiration date based on the chosen package's billing cycle duration multiplied by the interval.

#### Scope
- **In Scope:** New subscription form at `/subscriptions/new`, Member selector dropdown, Package selector dropdown, Interval multiplier input, automatic calculation of `expires_at` (`now() + cycle.num_days * interval`), default status assignment (`active`).
- **Out of Scope:** Payment gateway processing.

#### Preconditions
- At least one active member exists.
- At least one active membership package with a valid billing cycle exists.

#### Detailed Functional Description
Admin navigates to `/subscriptions/new`. Form prompts for: Member (User select dropdown), Package (Package select dropdown), and Interval (numeric multiplier, default 1). Submitting sends data to POST `/subscriptions`. The backend retrieves the package's linked billing cycle, calculates `expires_at = Carbon::now()->addDays($package->cycle->num_days * $interval)`, inserts the subscription record, and redirects back to `/subscriptions` with a success toast.

#### Business Rules
- **BR-041 (Expiration Date Calculation):** Expiration date is calculated as:
  $$\text{expires\_at} = \text{Current Timestamp} + (\text{Cycle.num\_days} \times \text{Interval})$$
- **BR-042 (Interval Constraints):** Interval must be a positive integer greater than or equal to 1.
- **BR-043 (Entity Existence Validation):** Both `user_id` and `package_id` must reference valid, existing database entities.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Member | Target gym member | Yes | Dropdown | Existing user ID | Required, Must exist in `users` | `Jane Doe (ID: 3)` |
| Package | Membership package | Yes | Dropdown | Existing package ID | Required, Must exist in `packages` | `Gold Monthly (ID: 2)` |
| Interval | Duration multiplier | Yes | Number | Integer >= 1 | Required, Min 1 | `2` (for 2 months) |

#### Acceptance Criteria

**AC-066: Successfully issue a 1-interval subscription**
- **Scenario:** Admin subscribes a member to a Monthly package (30 days) with interval = 1.
- **Conditions:** Package cycle = 30 days; Interval = 1.
- **Expected Behavior:** System creates subscription with `expires_at` set to exactly 30 days from now, status = `active`.
- **Expected Outcome:** Subscription is listed in `/subscriptions` with valid package, member name, and calculated expiry date.

**AC-067: Correct calculation with multiple intervals**
- **Scenario:** Admin subscribes a member to a Monthly package (30 days) with interval = 3.
- **Conditions:** Package cycle = 30 days; Interval = 3.
- **Expected Behavior:** System calculates expiration as 30 * 3 = 90 days from current timestamp.
- **Expected Outcome:** `expires_at` equals today + 90 days.

**AC-068: Validation of missing member or package selection**
- **Scenario:** Admin submits subscription form without selecting a package.
- **Conditions:** Package unselected.
- **Expected Behavior:** Form validation blocks submission or returns `The package id field is required.`.
- **Expected Outcome:** Form highlights missing package selection. No subscription created.

**AC-069: Rejection of zero or negative interval**
- **Scenario:** Admin inputs `0` or `-1` in Interval.
- **Conditions:** Interval < 1.
- **Expected Behavior:** Validation error triggers: `The interval must be at least 1.`.
- **Expected Outcome:** Submission is rejected.

#### Test Data Requirements
- Package: "Monthly Pass" (Cycle: 30 days).
- Member: `Jane Doe` (User ID: 2).
- Interval: 1 (for 30-day expiry), 3 (for 90-day expiry).

#### Dependencies & Isolation
- Requires existing Package and Member records.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable using pre-seeded package and user fixtures. |
| Negotiable | Yes | Describes expiration calculation logic. |
| Valuable | Yes | Core business transaction generating club access. |
| Estimable | Yes | Specific calculation and validation scope. |
| Small | Yes | Covers subscription creation only. |
| Testable | Yes | Verified by asserting calculated `expires_at` date against current timestamp. |

---

### US-020: Modify Subscription Status & Lifecycle Transitions
- **Module:** Subscriptions Management
- **Priority:** High
- **User Role:** Gym Administrator
- **Story Type:** Functional / Business Rule

#### User Story Statement
As a **Gym Administrator**,  
I want to **update a subscription's status (Active, Inactive, Expired, Suspended, Cancelled)**,  
So that **I can pause memberships during medical leaves, cancel accounts, or manually renew expired passes.**

#### Business Context
Members frequently request temporary freezes (e.g. vacation or injury) or cancellations. Staff must record these status transitions and capture suspension timestamps.

#### Scope
- **In Scope:** Subscription details page at `/subscriptions/[id]`, changing status dropdown (`active`, `inactive`, `expired`, `suspended`, `cancelled`), automatic recording of `suspended_at` timestamp when status transitions to `suspended`, subscription soft deletion.
- **Out of Scope:** Prorated billing refunds.

#### Preconditions
- An existing subscription record exists.

#### Detailed Functional Description
Admin navigates to `/subscriptions/[id]`. The form displays current member name, package, expiration date, and Status dropdown. Admin selects a new status. If the status is changed to `suspended`, the system automatically captures the current timestamp in `suspended_at`. Submitting saves changes to `subscriptions` and shows a success toast.

#### Business Rules
- **BR-044 (Valid Subscription Statuses):** Permitted subscription statuses are `active`, `inactive`, `expired`, `suspended`, and `cancelled`.
- **BR-045 (Suspension Timestamp Recording):** When status transitions to `suspended`, `suspended_at` must be automatically populated with `Carbon::now()`.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Status | Subscription lifecycle state | Yes | Dropdown | `active`, `inactive`, `expired`, `suspended`, `cancelled` | Must match status enum | `suspended` |
| Expiration Date | Membership expiration | No | Date / Datetime | Valid date format | Editable override | `2026-12-31` |

#### Acceptance Criteria

**AC-070: Suspend an active subscription**
- **Scenario:** Member requests medical freeze; admin changes status to `suspended`.
- **Conditions:** Subscription is currently `active`.
- **Expected Behavior:** System sets `status = 'suspended'` and records `suspended_at = now()`.
- **Expected Outcome:** Subscription record updates; `suspended_at` is populated with current timestamp.

**AC-071: Reactivate a suspended subscription**
- **Scenario:** Member returns from leave; admin sets status back to `active`.
- **Conditions:** Subscription is currently `suspended`.
- **Expected Behavior:** Status updates to `active`.
- **Expected Outcome:** Subscription reflects active standing; member can access gym.

**AC-072: Cancel a subscription**
- **Scenario:** Member requests termination; admin selects `cancelled`.
- **Conditions:** Target subscription exists.
- **Expected Behavior:** Status updates to `cancelled`.
- **Expected Outcome:** Subscription displays Cancelled status badge.

**AC-073: Delete / Terminate subscription**
- **Scenario:** Admin clicks Delete on an erroneous subscription.
- **Conditions:** Subscription exists.
- **Expected Behavior:** DELETE `/subscriptions/{id}` executes soft deletion.
- **Expected Outcome:** Subscription removed from active list; historical foreign keys preserved.

#### Test Data Requirements
- Subscription ID: 1 (Status: `active`).

#### Dependencies & Isolation
- Requires an existing subscription.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable directly on any existing subscription. |
| Negotiable | Yes | Describes status lifecycle rules. |
| Valuable | Yes | Supports member lifecycle freezes and cancellations. |
| Estimable | Yes | State transition and timestamp capture scope. |
| Small | Yes | Covers subscription status modification only. |
| Testable | Yes | Directly verified by inspecting database columns `status` and `suspended_at`. |

---

### US-021: System Activity Audit Trail & Log Monitoring
- **Module:** Audit & Compliance
- **Priority:** Low
- **User Role:** Gym Administrator
- **Story Type:** Functional / Informational

#### User Story Statement
As a **Gym Administrator**,  
I want to **review a chronologically sorted audit trail of system activities and filter by activity type**,  
So that **I can monitor system usage, track operations, and investigate historical actions.**

#### Business Context
Accountability and compliance require an immutable record of system events (e.g. attendance check-ins, user updates) indicating what entity was affected and when.

#### Scope
- **In Scope:** Activities directory at `/activities`, chronological descending order, columns (ID, Entity, Type, Description, Date), filtering by `type` and `entity_id`, pagination.
- **Out of Scope:** Exporting audit logs to CSV/PDF.

#### Preconditions
- Administrator is logged in.
- Activity records exist in `activities`.

#### Detailed Functional Description
Admin navigates to `/activities`. The table loads recent system activities sorted with newest events at the top (`orderBy('created_at', 'DESC')`). Admin can filter by type (`filter[type]=attendance`) or by entity ID. Each row displays the Activity ID, Type badge, formatted description, and relative/formatted creation date.

#### Business Rules
- **BR-046 (Chronological Ordering):** Activity logs must always be returned in descending chronological order (`created_at DESC`).
- **BR-047 (Audit Immutability):** Activity records represent historical events and should be treated as read-only audit records in the user interface.

#### Field Specifications
Filter and display fields:
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Type Filter | Filter by activity category | No | String | `attendance`, etc. | Valid string | `attendance` |
| Entity ID | Filter by target record ID | No | Integer | Numeric ID | Valid entity ID | `5` |

#### Acceptance Criteria

**AC-074: Render system activities in descending chronological order**
- **Scenario:** Admin opens `/activities`.
- **Conditions:** Multiple activities exist with varying timestamps.
- **Expected Behavior:** System queries `/activities` ordered by `created_at DESC`.
- **Expected Outcome:** The newest recorded activity appears as the very first table row.

**AC-075: Filter activities by type**
- **Scenario:** Admin filters audit trail for attendance records only.
- **Conditions:** Database contains attendance activities and other activity types.
- **Expected Behavior:** Query includes `filter[type]=attendance`.
- **Expected Outcome:** Table displays only records where type equals `attendance`.

**AC-076: Activity log pagination**
- **Scenario:** Activity logs exceed 15 records.
- **Conditions:** 25 total activities in database.
- **Expected Behavior:** Page 1 renders first 15 records; pagination shows Page 1 of 2.
- **Expected Outcome:** Navigating to Page 2 loads the remaining 10 older activities.

#### Test Data Requirements
- Activity 1: Type: `attendance`, Desc: `User 1 : Admin has logged in`, Created: 1 hour ago.
- Activity 2: Type: `attendance`, Desc: `User 2 : Jane has logged out`, Created: 10 minutes ago.

#### Dependencies & Isolation
- Testable with any seeded or generated activity records.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Read-only audit trail verification. |
| Negotiable | Yes | Describes log presentation and sorting rules. |
| Valuable | Yes | System compliance and security auditability. |
| Estimable | Yes | Simple filtered query and table display. |
| Small | Yes | Covers activity log viewing only. |
| Testable | Yes | Verified by asserting sort order and filter outcomes. |

---

### US-022: Member Self-Service Profile & Contact Update
- **Module:** Member PWA Self-Service
- **Priority:** High
- **User Role:** Authenticated Gym Member
- **Story Type:** Functional / Data Management

#### User Story Statement
As a **Gym Member**,  
I want to **view and update my personal contact details and residential address from the Member PWA**,  
So that **my gym profile has my up-to-date phone number and address without requiring staff assistance.**

#### Business Context
Empowers members to maintain their personal records independently from their mobile devices, reducing front-desk administrative inquiries.

#### Scope
- **In Scope:** Profile screen on Member PWA (`/profile`), pre-populating existing data via GET `/api/me`, editing Contact Number, Address, City, State, Country, Postcode, saving changes via PUT `/api/me`, updating `profiles` record.
- **Out of Scope:** Changing email address or member name (governed by club admin for identity protection).

#### Preconditions
- Member is logged into the Member PWA (`http://127.0.0.1:5174`).

#### Detailed Functional Description
The member taps "Profile" in the navigation. The app calls `/api/me`, pre-populating Contact Number, Address, City, State, Country, and Postcode. Member edits their contact information and taps "Save Changes". The form sends a PUT request to `/api/me`. On success, the linked `Profile` record is updated, local store user state is refreshed, and a success toast "Profile updated successfully." appears.

#### Business Rules
- **BR-048 (Member Self-Update Restrictions):** Members can self-update demographic profile attributes (`contact_number`, `address`, `city`, `state`, `country`, `postcode`), but cannot alter their primary legal name or email address via this form.
- **BR-049 (String Length Limits):** Contact Number (max 20 chars), Address (max 255 chars), City (max 100 chars), State (max 100 chars), Country (max 100 chars), Postcode (max 20 chars).

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Contact Number | Personal phone number | No | String | Max 20 characters | Max 20 chars | `+1 555 123 4567` |
| Address | Street address | No | String | Max 255 characters | Max 255 chars | `45 Ocean Drive` |
| City | City | No | String | Max 100 characters | Max 100 chars | `Miami` |
| State | State | No | String | Max 100 characters | Max 100 chars | `FL` |
| Country | Country name or code | No | String | Max 100 characters | Max 100 chars | `United States` |
| Postcode | ZIP / Postal code | No | String | Max 20 characters | Max 20 chars | `33139` |

#### Acceptance Criteria

**AC-077: Pre-populate profile data on page load**
- **Scenario:** Member navigates to `/profile`.
- **Conditions:** Member has existing address and phone data saved.
- **Expected Behavior:** App loads `/api/me` and fills input fields with existing values.
- **Expected Outcome:** Member sees their current phone number and address.

**AC-078: Successfully update profile details**
- **Scenario:** Member updates phone number and city, then clicks Save Changes.
- **Conditions:** Valid inputs within length limits.
- **Expected Behavior:** PUT `/api/me` updates `profiles` row; returns HTTP 200.
- **Expected Outcome:** Success toast "Profile updated successfully." appears; updated values persist.

**AC-079: Enforce field length constraints**
- **Scenario:** Member inputs a contact number with 25 characters.
- **Conditions:** String length > 20 characters.
- **Expected Behavior:** Backend validation triggers `The contact number must not be greater than 20 characters.`.
- **Expected Outcome:** Error toast displayed; database record remains unchanged.

#### Test Data Requirements
- Authenticated Member: `member@example.com` / `password`.
- Valid Data: Phone: `555-444-3333`, Address: `12 Fitness Blvd`, City: `Austin`, State: `TX`, Country: `USA`, Postcode: `78701`.

#### Dependencies & Isolation
- Requires an active member login session on port 5174.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Operates strictly within the member's own profile scope. |
| Negotiable | Yes | Describes self-update specifications. |
| Valuable | Yes | Core self-service feature for members. |
| Estimable | Yes | Standard profile update form. |
| Small | Yes | Covers profile demographic fields only. |
| Testable | Yes | Verified via UI feedback and database profile row checks. |

---

### US-023: Member Self-Service Password Change
- **Module:** Member PWA Self-Service & Security
- **Priority:** High
- **User Role:** Authenticated Gym Member
- **Story Type:** Security / Functional

#### User Story Statement
As a **Gym Member**,  
I want to **change my password from my mobile profile by providing my current password and a confirmed new password**,  
So that **I can maintain account security without contacting gym administration.**

#### Business Context
Members must be able to change passwords periodically or upon suspected compromise by verifying their existing credentials.

#### Scope
- **In Scope:** "Change Password" section on Member Profile (`/profile`), Current Password validation via Hash verification, New Password minimum length (8 characters), Confirmation matching, updating password hash.
- **Out of Scope:** Two-factor authentication (2FA).

#### Preconditions
- Member is logged into the Member PWA.

#### Detailed Functional Description
On the `/profile` screen under "Change Password", the member enters Current Password, New Password, and Confirm New Password. Tapping "Update Password" first checks client-side that New Password equals Confirm New Password. The request is submitted to PUT `/api/me/password`. The server verifies that Current Password matches the stored hash (`Hash::check`). If valid, the new password is encrypted and saved, form fields are reset, and a toast "Password updated successfully." is displayed.

#### Business Rules
- **BR-050 (Current Password Authentication):** The user must supply their correct existing password. If incorrect, the request is rejected with `The provided password does not match your current password.`.
- **BR-051 (New Password Standards):** The new password must be at least 8 characters long and match confirmation.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Current Password | Existing account password | Yes | String | Plain text string | Required, Must match current DB hash | `oldPass123` |
| New Password | Desired replacement password | Yes | String | Plain text string | Required, Min 8 characters | `newSecurePass88` |
| Confirm New Password | Re-entered new password | Yes | String | Plain text string | Required, Must match New Password | `newSecurePass88` |

#### Acceptance Criteria

**AC-080: Successfully change password with correct current password**
- **Scenario:** Member enters valid current password and matching 8+ character new password.
- **Conditions:** Current password matches hash; new password matches confirmation.
- **Expected Behavior:** System updates password hash in `users`; returns HTTP 200.
- **Expected Outcome:** Success toast "Password updated successfully." appears. Form fields clear. Member can log in with new password.

**AC-081: Reject incorrect current password**
- **Scenario:** Member mistypes their current password.
- **Conditions:** Current password does not match database hash.
- **Expected Behavior:** Validation error triggers: `The provided password does not match your current password.`.
- **Expected Outcome:** Password is not updated; error toast displayed.

**AC-082: Reject mismatched new password confirmation**
- **Scenario:** Member enters differing strings in New and Confirm fields.
- **Conditions:** New Password = `NewPass123`, Confirm = `DifferentPass456`.
- **Expected Behavior:** Client-side check catches mismatch before network dispatch.
- **Expected Outcome:** Error toast "Passwords do not match." is displayed. Request is blocked.

**AC-083: Enforce minimum 8-character constraint on member self-change**
- **Scenario:** Member enters a 6-character new password.
- **Conditions:** Password length < 8.
- **Expected Behavior:** HTML5 validation blocks submission (`minlength="8"`).
- **Expected Outcome:** Browser displays "Please lengthen this text to 8 characters or more".

#### Test Data Requirements
- Authenticated Member: `member@example.com` (current password: `password`).
- Valid New Password: `memberNewPass2026!`.
- Incorrect Current Password: `wrongPassword99`.

#### Dependencies & Isolation
- Requires active member session.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Testable on any authenticated member account. |
| Negotiable | Yes | Describes credential verification workflow. |
| Valuable | Yes | Critical account security feature. |
| Estimable | Yes | Clear verification rules and error paths. |
| Small | Yes | Covers self-service password update only. |
| Testable | Yes | Verified by attempting subsequent login with the new password. |

---

### US-024: Member Self-Service Subscriptions & History
- **Module:** Member PWA Self-Service
- **Priority:** High
- **User Role:** Authenticated Gym Member
- **Story Type:** Functional / Informational

#### User Story Statement
As a **Gym Member**,  
I want to **view my current active membership details and past subscription history**,  
So that **I can check my plan expiration date, package inclusions, and renewal timeline.**

#### Business Context
Gym members want transparency regarding their active membership standing, which services are included in their plan, and when their access will expire.

#### Scope
- **In Scope:** Subscriptions screen on Member PWA (`/subscription`), querying GET `/api/me/subscriptions`, highlighting the primary active subscription (package name, expiry countdown, status badge, included services list), listing past expired/cancelled subscriptions.
- **Out of Scope:** Online subscription renewal or e-commerce checkout.

#### Preconditions
- Member is logged into the Member PWA.

#### Detailed Functional Description
The member opens the "Subscription" tab. The app requests `/api/me/subscriptions`. The view splits into two sections:
1. **Current Membership:** Renders the most recent active subscription in a prominent hero card showing Package Name, Expiry date, Status badge (Active/Suspended/Expired), and the list of bundled amenities (e.g. Free Weights, Pool, Sauna).
2. **Subscription History:** Lists previous subscriptions with start and end dates and status badges.
If the member has no subscriptions, an informative empty state is displayed.

#### Business Rules
- **BR-052 (Data Scope Security):** Members can only view their own subscriptions (`user_id = auth()->id()`). Subscriptions belonging to other members must never be returned.

#### Field Specifications
Read-only display fields:
| Display Field | Description | Source Entity | Example |
|---|---|---|---|
| Package Name | Title of purchased bundle | `package.name` | `Gold All-Access Annual` |
| Status Badge | Standing of membership | `subscription.status` | `active` |
| Expiration Date | End date of gym access | `subscription.expires_at` | `December 31, 2026` |
| Included Services | List of gym amenities | `package.services[].name` | `Swimming Pool, Sauna, Weights` |

#### Acceptance Criteria

**AC-084: Display active subscription hero card with included amenities**
- **Scenario:** Member with an active subscription views the Subscription tab.
- **Conditions:** Member holds an active subscription linked to a package with 2 services.
- **Expected Behavior:** App displays package title, expiry date, status badge, and renders both included services.
- **Expected Outcome:** Member sees complete details of their current membership.

**AC-085: Display empty state when member has no subscriptions**
- **Scenario:** Newly registered member with no purchases opens Subscription tab.
- **Conditions:** Subscriptions array is empty (`[]`).
- **Expected Behavior:** System renders empty state card: "No active subscription" / "No subscription history yet.".
- **Expected Outcome:** Clean message displayed without rendering errors.

**AC-086: List historical expired subscriptions**
- **Scenario:** Member has 1 active and 2 expired past subscriptions.
- **Conditions:** Database contains 3 subscriptions for this user.
- **Expected Behavior:** Active plan displays in hero card; the other 2 display in the History list below.
- **Expected Outcome:** Complete chronological history is visible.

#### Test Data Requirements
- Member with active subscription: `member@example.com` (Sub ID: 1, Package: "Gold Annual").
- Member with no subscriptions: `new_user@example.com`.

#### Dependencies & Isolation
- Requires existing subscription records linked to the member.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Read-only presentation of member's own subscription records. |
| Negotiable | Yes | Describes data layout and empty state rules. |
| Valuable | Yes | Direct member visibility into their membership status. |
| Estimable | Yes | Read-only list display. |
| Small | Yes | Covers member subscription viewing only. |
| Testable | Yes | Verified by comparing UI cards against database subscription records. |

---

### US-025: Member Gym Attendance History & Monthly Filtering
- **Module:** Member PWA Self-Service
- **Priority:** Medium
- **User Role:** Authenticated Gym Member
- **Story Type:** Functional / Informational

#### User Story Statement
As a **Gym Member**,  
I want to **view my gym visit attendance history and filter records by calendar month**,  
So that **I can track my workout frequency and verify my gym check-ins.**

#### Business Context
Fitness enthusiasts track their visit consistency and workout frequency over time to stay motivated and verify club check-ins.

#### Scope
- **In Scope:** Attendance screen on Member PWA (`/attendance`), querying GET `/api/me/attendance`, month filter picker (`YYYY-MM`), visit cards (Date badge, Branch Name, Day/Time, Description), pagination controls.
- **Out of Scope:** Manual attendance self-logging (logged via front desk or automated reader).

#### Preconditions
- Member is logged into the Member PWA.

#### Detailed Functional Description
Member opens the "Attendance" view. The app queries `/api/me/attendance`. Each entry displays a date badge with the day of the month, the branch location name, full date, check-in time, and visit description. The member can use the month filter picker (`<input type="month">`) to narrow visits to a specific month (e.g. `2026-11`), which passes `?month=2026-11` to the API. If no visits occurred in that month, an empty state is shown.

#### Business Rules
- **BR-053 (Member Attendance Isolation):** Attendance history must only return records where `user_id` matches the authenticated member's ID.
- **BR-054 (Month Filter Format):** The month filter matches against check-in timestamp in format `YYYY-MM`.

#### Field Specifications
| Field Name | Description | Mandatory | Data Type | Accepted Format / Values | Validation Rules | Example |
|---|---|---|---|---|---|---|
| Filter by Month | Calendar month filter | No | Date / Month | `YYYY-MM` format | Standard year-month | `2026-11` |

#### Acceptance Criteria

**AC-087: Display list of past gym visits**
- **Scenario:** Member with recorded check-ins opens Attendance view.
- **Conditions:** Member has check-in records in database.
- **Expected Behavior:** System renders visit cards with Branch Name, Date, and Time.
- **Expected Outcome:** Visits appear in descending order of check-in time.

**AC-088: Filter attendance records by month**
- **Scenario:** Member selects a specific month from the month picker.
- **Conditions:** Visits exist in November 2026 and October 2026; member picks `2026-11`.
- **Expected Behavior:** App requests `/api/me/attendance?month=2026-11`.
- **Expected Outcome:** Table updates to show only November 2026 visits.

**AC-089: Display empty state when no visits match selected month**
- **Scenario:** Member selects a month in which they did not visit the gym.
- **Conditions:** 0 records for selected month.
- **Expected Behavior:** System displays empty state card: "No visits recorded for this month.".
- **Expected Outcome:** Friendly message displayed without UI failure.

#### Test Data Requirements
- Attendance records for User ID 2: 2 check-ins in November 2026, 1 check-in in October 2026.

#### Dependencies & Isolation
- Requires seeded or kiosk-generated attendance records for the target user.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Read-only filtered list of member visits. |
| Negotiable | Yes | Describes visit history and filtering behavior. |
| Valuable | Yes | Workout tracking transparency for gym members. |
| Estimable | Yes | List display with single query parameter filter. |
| Small | Yes | Covers member attendance viewing only. |
| Testable | Yes | Verified by checking date filtering against database records. |

---

### US-026: Member Branch Directory & Home Branch Identification
- **Module:** Member PWA & Branch Directory
- **Priority:** Low
- **User Role:** Authenticated Gym Member
- **Story Type:** Functional / Informational

#### User Story Statement
As a **Gym Member**,  
I want to **view the directory of gym branches and identify which locations are assigned to my membership**,  
So that **I know where I am eligible to work out and can find club contact details.**

#### Business Context
In a multi-club network, members need to know which locations they are authorized to enter and find branch phone numbers and addresses.

#### Scope
- **In Scope:** Branches screen on Member PWA (`/branches`), querying GET `/branches` and GET `/me`, displaying branch name, address, phone, email, and highlighting branches assigned to the member with a "Your Branch" badge.
- **Out of Scope:** GPS directions, branch class schedule booking.

#### Preconditions
- Member is logged into the Member PWA.
- Branches exist in the database.

#### Detailed Functional Description
Member taps "Branches" in the navigation. The app executes parallel requests for `/branches` and `/me`. It iterates over all active branches. If a branch's ID exists in the member's assigned branches or active subscriptions, it is highlighted with a primary border and displays a "Your Branch" badge. Branch phone numbers render as clickable `tel:` links and emails as `mailto:` links.

#### Business Rules
- **BR-055 (Home Branch Matching):** A branch is marked as "Your Branch" if its ID matches any branch in `me.branches` or `me.subscriptions[].branch_id`.

#### Field Specifications
Read-only display elements:
| Display Field | Description | Source Entity | Example |
|---|---|---|---|
| Branch Name | Club location title | `branch.name` | `Central Downtown Club` |
| Your Branch Badge | Visual indicator of member access | Matched via `memberBranchIds.has(branch.id)` | `Your Branch` (Badge) |
| Address / City / State | Physical address | `branch.address`, `city`, `state` | `100 Main St, New York, NY` |
| Phone Link | Telephone dialer link | `branch.phone` | `(555) 234-5678` |

#### Acceptance Criteria

**AC-090: Display all active gym locations**
- **Scenario:** Member opens Branches view.
- **Conditions:** 3 active branches exist in database.
- **Expected Behavior:** System renders 3 branch cards with location names and contact details.
- **Expected Outcome:** Complete club network is visible.

**AC-091: Highlight assigned member branch**
- **Scenario:** Member is assigned to Branch 1 ("Central"), but not Branch 2 ("East").
- **Conditions:** Member's linked branches include Branch 1.
- **Expected Behavior:** Branch 1 renders with primary border and "Your Branch" badge; Branch 2 renders standard styling.
- **Expected Outcome:** Member easily identifies their home club.

**AC-092: Clickable phone and email links**
- **Scenario:** Member taps the phone number on a branch card.
- **Conditions:** Branch has phone number populated.
- **Expected Behavior:** Browser triggers `tel:` protocol handler.
- **Expected Outcome:** Device phone dialer opens with the branch phone number pre-filled.

#### Test Data Requirements
- Branch 1: "Central City", assigned to User 2.
- Branch 2: "Suburban West", not assigned to User 2.

#### Dependencies & Isolation
- Requires existing branch and member assignment data.

#### INVEST Assessment
| INVEST Principle | Satisfied? | Explanation |
|---|---|---|
| Independent | Yes | Read-only presentation of branch directory. |
| Negotiable | Yes | Outlines directory presentation and badge indicators. |
| Valuable | Yes | Informs members of club locations and contact details. |
| Estimable | Yes | Simple list display with matching logic. |
| Small | Yes | Covers branch discovery for members only. |
| Testable | Yes | Verified by checking badge presence against member branch assignments. |

---

## 4. Manual QA Training Recommendations

### Tier 1: Beginner Level (CRUD, UI Navigation & Form Validation)
* **Target Stories:** `US-008`, `US-009` (Billing Cycles), `US-010` (Services), `US-011` (Branches).
* **Skills Practiced:**
  - Mandatory field boundary testing (empty strings, whitespace).
  - Maximum string length testing (255 characters).
  - Positive integer validation (`num_days > 0`).
  - Table search, filtering, and pagination verification.

### Tier 2: Intermediate Level (Entity Relationships, Multi-Select & Calculations)
* **Target Stories:** `US-012` (Packages), `US-017` (Branch Assignments), `US-019` (Subscriptions Calculation).
* **Skills Practiced:**
  - Relational integrity testing (linking packages to cycles and multiple services).
  - Equivalence partitioning & boundary-value analysis on duration calculations (Days x Interval).
  - Pivot table synchronization testing (adding, updating, and removing multi-select items).

### Tier 3: Advanced Level (Security, Authorization & State Lifecycles)
* **Target Stories:** `US-001`, `US-002`, `US-003` (Authentication & Access Control), `US-018` (Admin Self-Protection), `US-020` (Subscription Status Transitions), `US-023` (Password Security).
* **Skills Practiced:**
  - Role-based access control (RBAC): verifying non-admin rejection from the Admin Panel.
  - State transition testing (Active -> Suspended -> Active -> Cancelled) and verifying timestamp capture (`suspended_at`).
  - Negative security testing (token reuse, expired reset tokens, password hash comparisons).
  - Session lifecycle testing (route guarding, token purging on logout).
