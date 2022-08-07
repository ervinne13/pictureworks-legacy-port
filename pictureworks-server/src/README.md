# The `src` Folder

We use this folder for anything that should belong to business rules that is best decoupled from laravel. 

For example, in a toy Payroll system, the applicant uses this folder to house computations, business rules, etc. This is to help to really conform to SOLID and easily spot code smell whenever we are violating SRP.

For now, there's nothing to put here as there's not much business logic. Let's put the app key validation here though even that is a gray area, it could still be Laravel's domain but let's put it here anyway just to demonstrate we can update PSR-4 configs to allow for this setup.