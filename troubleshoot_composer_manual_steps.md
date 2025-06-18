# Manual Steps to Fix Composer Install Issues on Windows

If you are unable to delete the vendor directory and composer.lock file due to permission or locked file issues, follow these steps:

1. **Close Running PHP and Web Server Processes**
   - Close any running PHP built-in server, Apache, Nginx, or other web servers.
   - Close any running PHP CLI processes.
   - You can check running PHP processes in Task Manager and end them.

2. **Run PowerShell or Command Prompt as Administrator**
   - Right-click on PowerShell or Command Prompt and select "Run as administrator".

3. **Manually Delete the `vendor` Directory**
   - Open File Explorer.
   - Navigate to your project directory `C:\project\eduSmart`.
   - Delete the `vendor` folder manually.
   - If you get permission errors, ensure no processes are locking files.

4. **Manually Delete the `composer.lock` File**
   - In the same directory, delete the `composer.lock` file.

5. **Run Composer Install**
   - In the administrator PowerShell or Command Prompt, navigate to your project directory:
     ```
     cd C:\project\eduSmart
     ```
   - Run:
     ```
     composer install
     ```
   - Wait for dependencies to be installed.

6. **Verify Installation**
   - Check that the `vendor` directory is recreated with all dependencies.
   - Run your Laravel application to confirm the error is resolved.

---

If you want, I can guide you through these steps interactively or help with any other code changes or testing.
