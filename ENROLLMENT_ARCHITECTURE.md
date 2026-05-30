# Enrollment System Architecture Diagram

## Data Flow

```
┌─────────────────────────────────────────────────────────────┐
│                    Admin User                                │
│            (Enrollment Management Page)                      │
└────────────────┬────────────────────────────────────────────┘
                 │
                 ├─→ Selects Students, Teachers, Subjects
                 ├─→ Clicks "Save Changes"
                 │
┌────────────────┴────────────────────────────────────────────┐
│          ClassEnrollmentController::update()                │
│                                                              │
│  1. Validates request data                                  │
│  2. Calls EnrollmentService::enrollDepartment()            │
│  3. Returns result with statistics                          │
└────────────────┬───────────────────────────────────────────┘
                 │
┌────────────────┴───────────────────────────────────────────┐
│          EnrollmentService::enrollDepartment()              │
│                                                              │
│  ┌──────────────────────────────────────────────────┐       │
│  │ 1. Validate with EnrollmentValidator             │       │
│  │    - Check role IDs                              │       │
│  │    - Check for duplicates                        │       │
│  │    - Detect conflicts                            │       │
│  └──────────────────────────────────────────────────┘       │
│                        ↓                                     │
│  ┌──────────────────────────────────────────────────┐       │
│  │ 2. Get/Create Classes                            │       │
│  │    - Query Department→Majors→ClassModels        │       │
│  │    - Auto-create if none exist                  │       │
│  └──────────────────────────────────────────────────┘       │
│                        ↓                                     │
│  ┌──────────────────────────────────────────────────┐       │
│  │ 3. Begin Transaction                             │       │
│  └──────────────────────────────────────────────────┘       │
│                        ↓                                     │
│  ┌──────────────────────────────────────────────────┐       │
│  │ 4. Enroll Students                               │       │
│  │    - For each class:                             │       │
│  │      • Detach old students                       │       │
│  │      • Attach new students (role='student')     │       │
│  │      • Log to EnrollmentHistory                 │       │
│  └──────────────────────────────────────────────────┘       │
│                        ↓                                     │
│  ┌──────────────────────────────────────────────────┐       │
│  │ 5. Enroll Teachers                               │       │
│  │    - For each class:                             │       │
│  │      • Detach old teachers                       │       │
│  │      • Attach new teachers (role='teacher')    │       │
│  │      • Log to EnrollmentHistory                 │       │
│  └──────────────────────────────────────────────────┘       │
│                        ↓                                     │
│  ┌──────────────────────────────────────────────────┐       │
│  │ 6. Assign Subjects                               │       │
│  │    - For each class:                             │       │
│  │      • Sync subjects (replaces all)             │       │
│  │      • Log additions/removals                    │       │
│  └──────────────────────────────────────────────────┘       │
│                        ↓                                     │
│  ┌──────────────────────────────────────────────────┐       │
│  │ 7. Commit Transaction                            │       │
│  │    (or Rollback on error)                        │       │
│  └──────────────────────────────────────────────────┘       │
│                        ↓                                     │
│  Return: { success, message, statistics, errors }           │
└────────────────┬────────────────────────────────────────────┘
                 │
┌────────────────┴────────────────────────────────────────────┐
│              Database Updates (Atomic)                       │
│                                                              │
│  class_user table:                                          │
│  ┌────────────────────────────────────────┐                │
│  │ id │ class_model_id │ user_id │ role   │                │
│  ├────────────────────────────────────────┤                │
│  │ 1  │ 1              │ 10      │student │                │
│  │ 2  │ 1              │ 11      │student │                │
│  │ 3  │ 1              │ 20      │teacher │                │
│  └────────────────────────────────────────┘                │
│                                                              │
│  class_subject table:                                       │
│  ┌────────────────────────────────────┐                    │
│  │ id │ class_model_id │ subject_id  │                    │
│  ├────────────────────────────────────┤                    │
│  │ 1  │ 1              │ 5           │                    │
│  │ 2  │ 1              │ 6           │                    │
│  └────────────────────────────────────┘                    │
│                                                              │
│  enrollment_histories table:                                │
│  ┌────────────────────────────────────────────────────┐    │
│  │ id │ action  │ user_id │ class_id │ admin_id │ ... │    │
│  ├────────────────────────────────────────────────────┤    │
│  │ 1  │enrolled │ 10      │ 1        │ 1        │ ... │    │
│  │ 2  │enrolled │ 11      │ 1        │ 1        │ ... │    │
│  │ 3  │enrolled │ 20      │ 1        │ 1        │ ... │    │
│  └────────────────────────────────────────────────────┘    │
└──────────────────────────────────────────────────────────────┘
```

## Service Architecture

```
┌─────────────────────────────────────────┐
│         ClassEnrollmentController        │
├─────────────────────────────────────────┤
│ + index()                                │
│ + manage()                               │
│ + update()           ──→ EnrollmentService
│ + export()           ──→ EnrollmentService
│ + import()           ──→ EnrollmentService
│ + statistics()       ──→ EnrollmentService
│ + history()          ──→ EnrollmentHistory
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│     EnrollmentService                    │
├─────────────────────────────────────────┤
│ + enrollDepartment()                     │
│ + getOrCreateClasses()                   │
│ + enrollStudents()                       │
│ + enrollTeachers()                       │
│ + assignSubjects()                       │
│ + getEnrollmentStats()                   │
│ + getEnrollmentHistory()                 │
│ + exportToCSV()                          │
│ + importFromCSV()                        │
│                                          │
│ Uses:                                    │
│  ├─ EnrollmentValidator                  │
│  └─ EnrollmentHistory                    │
└─────────────────────────────────────────┘
           ↓
┌─────────────────────────────────────────┐
│     EnrollmentValidator                  │
├─────────────────────────────────────────┤
│ + validateStudentEnrollment()            │
│ + validateTeacherEnrollment()            │
│ + validateSubjectAssignment()            │
│ + validateDepartmentEnrollment()         │
│ + checkConflicts()                       │
│ + getErrors()                            │
│ + getWarnings()                          │
│ + getStatus()                            │
└─────────────────────────────────────────┘
```

## View/Route Architecture

```
┌────────────────────────────────────────────────────────────┐
│                 Admin Enrollment Dashboard                 │
│            /admin/enrollments                              │
└──┬─────────────┬────────────────┬─────────────────┬────────┘
   │             │                │                 │
   ↓             ↓                ↓                 ↓
┌──────────┐ ┌────────┐ ┌─────────────┐ ┌──────────────┐
│ List All │ │Manage  │ │Statistics   │ │History/Audit │
│/index    │ │/{dept} │ │/{dept}/stat │ │/{dept}/hist  │
└──────────┘ └────────┘ └─────────────┘ └──────────────┘
     │            │              │             │
     ↓            ↓              ↓             ↓
   Views:    manage.blade   statistics.blade  history.blade
   - Lists   - Form         - Charts          - Audit table
   - Depts   - Checkboxes   - Cards           - Filters
   - Stats   - Submit       - Timeline        - Pagination
             - Modals       - History link

┌────────────────────────────────────────────────────────────┐
│              More Actions Dropdown Menu                    │
│         (From Manage Page: More Button)                    │
├────────────────────────────────────────────────────────────┤
│                                                             │
│  ├─→ Export CSV        /admin/enrollments/{dept}/export   │
│  │    Returns: CSV file download                          │
│  │                                                          │
│  ├─→ Import CSV        /admin/enrollments/{dept}/import   │
│  │    Shows: import.blade view with upload                │
│  │    Processes: POST to same route                       │
│  │                                                          │
│  ├─→ Statistics        /admin/enrollments/{dept}/stat     │
│  │    Shows: statistics.blade dashboard                   │
│  │                                                          │
│  └─→ History           /admin/enrollments/{dept}/history  │
│       Shows: history.blade with audit log                 │
│                                                             │
└────────────────────────────────────────────────────────────┘
```

## Data Relationships

```
┌────────────────┐
│  Department    │
│  ┌──────────┐  │
│  │ id       │  │
│  │ name     │  │
│  └──────────┘  │
└────────┬───────┘
         │
         │ hasMany
         │
         ↓
┌────────────────┐         ┌────────────────┐
│    Major       │◄─────────│  ClassModel    │
│  ┌──────────┐  │ hasMany  │  ┌──────────┐ │
│  │ id       │  │          │  │ id       │ │
│  │ dept_id  │  │          │  │ major_id │ │
│  │ name     │  │          │  │ code     │ │
│  └──────────┘  │          │  │ name     │ │
└────────────────┘          │  └──────────┘ │
                            └────────┬───────┘
                                     │
                                     │ belongsToMany
                                     ├─────────────────────┐
                                     │                     │
                        ┌────────────▼──────────┐  ┌───────▼──────┐
                        │    class_user         │  │ class_subject│
                        │  (pivot table)        │  │ (pivot table)│
                        │ ┌────────────────┐    │  │┌────────────┐│
                        │ │id              │    │  ││id          ││
                        │ │class_model_id  │    │  ││class_id    ││
                        │ │user_id         │    │  ││subject_id  ││
                        │ │role (pivot)    │    │  │└────────────┘│
                        │ └────────────────┘    │  └──────┬───────┘
                        └──────────┬───────────┘         │
                                   │                     │
                        ┌──────────▼─┐      ┌────────────▼──┐
                        │   Users    │      │   Subjects    │
                        │┌──────────┐│      │┌────────────┐ │
                        ││ id       ││      ││ id         │ │
                        ││ username ││      ││ name       │ │
                        ││ role_id  ││      ││ code       │ │
                        │└──────────┘│      │└────────────┘ │
                        └────────────┘      └────────────────┘

┌────────────────────────┐
│ EnrollmentHistory      │ (Audit Log)
│ ┌──────────────────┐   │
│ │ id               │   │
│ │ department_id    │   │
│ │ user_id          │   │
│ │ class_model_id   │   │
│ │ subject_id       │   │
│ │ action           │   │
│ │ action_type      │   │
│ │ admin_id         │   │
│ │ old_value (JSON) │   │
│ │ new_value (JSON) │   │
│ │ created_at       │   │
│ └──────────────────┘   │
└────────────────────────┘
```

## CSV Import/Export Flow

```
┌──────────────────────────────────────────┐
│        CSV File (enrollment.csv)         │
│  Class Name,User Type,Username,Subject   │
│  "CS101",Student,john,Programming        │
│  "CS101",Teacher,prof_x,Programming      │
└──────┬───────────────────────────────────┘
       │
       │ Upload / Paste
       │
┌──────▼────────────────────────────────┐
│  ClassEnrollmentController::import()   │
│                                        │
│  1. Read file content                 │
│  2. Parse CSV lines                   │
│  3. Validate each line                │
│  4. Call EnrollmentService::import()  │
└──────┬────────────────────────────────┘
       │
┌──────▼────────────────────────────────┐
│  EnrollmentService::importFromCSV()    │
│                                        │
│  1. Parse CSV                         │
│  2. Find users by username/email      │
│  3. Find subjects by name             │
│  4. Build enrollment arrays           │
│  5. Call enrollDepartment()           │
│  6. Return statistics                 │
└──────┬────────────────────────────────┘
       │
┌──────▼────────────────────────────────┐
│  Database Updates (as normal flow)     │
│  ✓ class_user entries created         │
│  ✓ class_subject entries created      │
│  ✓ enrollment_histories logged        │
└──────┬────────────────────────────────┘
       │
┌──────▼────────────────────────────────┐
│  Return Import Summary                 │
│  - Processed: X records                │
│  - Students Enrolled: Y                │
│  - Teachers Enrolled: Z                │
│  - Subjects Assigned: W                │
│  - Errors: [list]                      │
└──────────────────────────────────────────┘
```

## Error Handling & Validation Flow

```
┌─────────────────────────────────────────┐
│   EnrollmentValidator::validate()       │
├─────────────────────────────────────────┤
│                                          │
│  Step 1: Check User Roles              │
│  ├─ Student role_id must = 3           │
│  ├─ Teacher role_id must = 2           │
│  └─ Error if mismatch                  │
│                                          │
│  Step 2: Check Duplicates              │
│  ├─ Query class_user table             │
│  ├─ Find existing enrollments          │
│  └─ Warning if duplicate               │
│                                          │
│  Step 3: Check Classes Exist           │
│  ├─ Query ClassModel                   │
│  ├─ Find by major_id                   │
│  └─ Error if not found                 │
│                                          │
│  Step 4: Check Subjects Valid          │
│  ├─ Query Subject table                │
│  ├─ Find by id                         │
│  └─ Error if not found                 │
│                                          │
│  Step 5: Detect Conflicts              │
│  ├─ Check teacher scheduling           │
│  ├─ Find overlaps                      │
│  └─ Warning if conflict                │
│                                          │
│  Return: ValidationStatus {             │
│    valid: bool,                         │
│    errors: [],                          │
│    warnings: []                         │
│  }                                      │
│                                          │
└─────────────────────────────────────────┘
          │
          ↓ If Valid
┌─────────────────────────────────────────┐
│   EnrollmentService::enrollDepartment() │
│   (Process enrollment - see main flow)  │
└─────────────────────────────────────────┘
          │
          ↓ On Error
┌─────────────────────────────────────────┐
│   Database Transaction Rollback         │
│   Return: { success: false, errors: [] }│
└─────────────────────────────────────────┘
```

This architecture ensures:
- ✅ All enrollments are validated
- ✅ All changes are logged  
- ✅ All operations are atomic (all-or-nothing)
- ✅ Easy to extend with new features
- ✅ Clear separation of concerns
- ✅ Testable components
