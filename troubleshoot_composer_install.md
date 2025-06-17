# Troubleshooting Composer Install Issues in Laravel Project

The error "Failed to open stream: No such file or directory" for vendor files like `brick/math/src/BigNumber.php` indicates incomplete or failed composer installation.

## Common Causes
- Insufficient write permissions on the project directory.
- Antivirus or system software locking files or folders.
- Network issues preventing composer from downloading packages.
- Corrupted vendor directory or composer.lock file.

## Recommended Steps to Fix

1. **Check Directory Permissions**
   - Ensure the project directory (`c:/project/eduSmart`) and all subdirectories have full read/write permissions for your user.
   - On Windows, right-click the folder > Properties > Security tab > Edit permissions.

2. **Temporarily Disable Antivirus**
   - Some antivirus or security software may block file creation.
   - Temporarily disable it during composer install.

3. **Run Composer Commands with Administrator Privileges**
   - Open your terminal or PowerShell as Administrator.
   - Navigate to the project directory.

4. **Clear Composer Cache**
   ```bash
   composer clear-cache
   ```

5. **Delete Vendor Directory and Composer Lock File**
   - Delete the `vendor` folder inside your project.
   - Delete the `composer.lock` file.

6. **Run Composer Install**
   ```bash
   composer install
   ```

7. **Verify Installation**
   - Ensure no errors occur.
   - Check that the `vendor` directory contains all dependencies.

## Additional Tips

- If you are using PowerShell, avoid chaining commands with `&&`. Run commands separately.
- Ensure your internet connection is stable.
- If errors persist, consider reinstalling Composer.

---

If you want, I can help you run these commands step-by-step or assist with further troubleshooting.
