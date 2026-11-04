# WRC Haven Resort Booking System
## Requirements Traceability Matrix (RTM)

| Req ID | Requirement | System Module | Test Case ID | Verification |
|---|---|---|---|---|
| REQ-01 | Visitors should view available rooms. | rooms.php | TC01 | Verify room display |
| REQ-02 | Visitors should submit a booking. | booking.php | TC02 | Verify booking submission |
| REQ-03 | The system should validate customer details. | booking.php | TC03 | Verify input validation |
| REQ-04 | The system should store booking information. | db.php, booking.php | TC04 | Verify database storage |
| REQ-05 | Administrators should log in securely. | login.php | TC05 | Verify authentication |
| REQ-06 | Administrators should view customer bookings. | admin.php | TC06 | Verify booking retrieval |
| REQ-07 | Administrators should edit bookings. | edit_booking.php | TC07 | Verify booking modification |
| REQ-08 | Administrators should delete bookings. | delete_booking.php | TC08 | Verify booking deletion |
| REQ-09 | Administrators should update booking status. | update_status.php | TC09 | Verify status update |
| REQ-10 | Visitors should make simulated bank card payments. | payment.php, process_payment.php | TC10 | Verify payment processing |
| REQ-11 | The system should generate payment receipts. | receipt.php | TC11 | Verify receipt generation |
| REQ-12 | The system should protect administrator pages. | login.php, admin.php | TC12 | Verify access control |
| REQ-13 | The system should support administrator logout. | logout.php | TC13 | Verify session termination |
| REQ-14 | The system should maintain payment records. | payments table | TC14 | Verify payment storage |
| REQ-15 | The system should support version control and collaboration. | GitHub repository | TC15 | Verify repository and commits |

## Traceability Summary

The RTM connects the system requirements to the
implementation modules and software test cases.

Each requirement should be verified through testing.

Test results should be recorded as Pass or Fail
after the corresponding test case is executed.