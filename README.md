# MPKK Attendance System

A modern, mobile-responsive web application for MPKK (Majlis Pengurusan Komuniti Kampung) attendance confirmation.

## Features

- **Simple Attendance Form** with only 4 essential fields
- **Modern UI/UX Design** with animated gradient backgrounds
- **Mobile-Responsive** design that works on all devices
- **Real-time Validation** for all form inputs
- **Results Dashboard** with filtering and search capabilities
- **Clean Data Storage** using MySQL database

## Form Fields

1. **Nama** - Full name (automatically converted to uppercase)
2. **No. Telefon** - Phone number (10-11 digits, numbers only)
3. **MPKK** - Dropdown selection from 17 MPKK locations
4. **Jawatan Dalam MPKK** - Position (Pengerusi, Setiausaha, or Bendahari)

## MPKK Locations

The system includes the following MPKK locations:
- MPKK BERTAM INDAH
- MPKK PMTG TINGGI B
- MPKK PAYA KELADI
- MPKK LADANG MALAKOF
- MPKK PMTG LANGSAT
- MPKK PINANG TUNGGAL
- MPKK KG BAHARU
- MPKK KUBANG MENERONG
- MPKK KG TOK BEDU
- MPKK KG SELAMAT RANCANGAN
- MPKK KG SELAMAT SELATAN
- MPKK JALAN KEDAH
- MPKK KG DATUK
- MPKK PMTG SINTOK
- MPKK PMTG RAMBAI
- MPKK PADANG BENGGAL
- MPKK KUALA MUDA

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Setup Steps

1. **Clone or download** the project files to your web server directory

2. **Configure database connection** in `database.php`:
   ```php
   $host = 'localhost';
   $dbname = 'mpkk_attendance';
   $username = 'root';
   $password = '';
   ```

3. **Run the database setup**:
   - Navigate to `http://your-domain/setup_db.php` in your browser
   - You should see "Database setup completed successfully!"

4. **Access the application**:
   - Form: `http://your-domain/index.php`
   - Results: `http://your-domain/results.php`

## File Structure

```
MPKKform/
├── index.php          # Main attendance form
├── submit.php         # Form submission handler
├── results.php        # Results listing and filtering
├── database.php       # Database configuration
├── setup_db.php       # Database setup script
└── README.md          # This file
```

## Database Schema

**Table: `mpkk_attendance`**

| Column      | Type          | Description                    |
|-------------|---------------|--------------------------------|
| id          | INT           | Primary key (auto-increment)   |
| nama        | VARCHAR(255)  | Full name (uppercase)          |
| no_telefon  | VARCHAR(20)   | Phone number (digits only)     |
| mpkk        | VARCHAR(255)  | Selected MPKK location         |
| jawatan     | VARCHAR(100)  | Position in MPKK               |
| created_at  | TIMESTAMP     | Submission timestamp           |

## Features Details

### Form Validation
- **Client-side**: Real-time validation with visual feedback
- **Server-side**: Comprehensive validation for data integrity
- **Auto-formatting**: Name to uppercase, phone to digits only

### Results Page
- **Statistics**: Total attendance count
- **Filtering**: By search term, MPKK, position, and date range
- **Responsive Table**: Mobile-friendly data display
- **Clean Interface**: No approval features, simple listing

### Design Highlights
- **Gradient Background**: Purple to blue animated gradient
- **Floating Elements**: Subtle background animations
- **Card-based Layout**: Modern card design with shadows
- **Smooth Transitions**: Hover effects and animations
- **Mobile-First**: Responsive design for all screen sizes

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Security Features

- PDO prepared statements to prevent SQL injection
- Input sanitization and validation
- Session-based flash messages
- CSRF protection ready (can be added)

## Customization

### Changing Colors
Edit the CSS variables in `index.php`:
```css
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --bg-gradient-start: #667eea;
    --bg-gradient-end: #764ba2;
}
```

### Adding More MPKK Locations
Edit the MPKK dropdown in `index.php` and `results.php`:
```html
<option value="NEW MPKK NAME">NEW MPKK NAME</option>
```

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check database credentials in `database.php`
- Ensure database exists (run `setup_db.php`)

### Session Warnings
- Ensure `session_start()` is at the very top of PHP files
- Check PHP session configuration

### Form Not Submitting
- Check browser console for JavaScript errors
- Verify `submit.php` has proper permissions
- Check database connection

## License

This project is open source and available for use.

## Support

For issues or questions, please contact the development team.

---

© 2026 Majlis Pengurusan Komuniti Kampung
