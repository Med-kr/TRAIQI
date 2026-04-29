# Diagramme de Classes

Ce diagramme représente les classes métier principales du projet et leurs relations.

```mermaid
classDiagram
    class User {
        +id
        +uuid
        +global_code
        +name
        +email
        +password
        +school_id
        +level_id
        +primaryRole()
        +dashboardRoute()
    }

    class School {
        +id
        +name
    }

    class Level {
        +id
        +school_id
        +code
        +name
        +order
    }

    class AcademicYear {
        +id
        +school_id
        +name
        +start_date
        +end_date
        +is_current
    }

    class Classroom {
        +id
        +school_id
        +academic_year_id
        +level_id
        +name
    }

    class Subject {
        +id
        +school_id
        +name
    }

    class TeacherAssignment {
        +id
        +school_id
        +teacher_id
        +classroom_id
        +subject_id
        +evaluationCount()
    }

    class Evaluation {
        +id
        +school_id
        +classroom_id
        +subject_id
        +academic_year_id
        +teacher_id
        +title
        +date
    }

    class Grade {
        +id
        +school_id
        +evaluation_id
        +student_id
        +value
    }

    class GradeComment {
        +id
        +grade_id
        +teacher_id
        +comment
    }

    class ReviewRequest {
        +id
        +grade_id
        +student_id
        +reason
        +status
    }

    class Notification {
        +id
        +user_id
        +title
        +body
        +read
    }

    class Import {
        +id
        +school_id
        +user_id
        +type
        +file_name
        +status
    }

    class AuditLog {
        +id
        +user_id
        +action
        +description
    }

    class StudentProfile {
        +id
        +user_id
        +classroom_id
        +cin
        +phone
    }

    class ParentProfile {
        +id
        +user_id
        +phone
    }

    class TeacherProfile {
        +id
        +user_id
        +phone
        +specialty
    }

    class AdministrationProfile {
        +id
        +user_id
        +phone
        +position
    }

    School "1" --> "many" User : contains
    School "1" --> "many" Level : contains
    School "1" --> "many" AcademicYear : contains
    School "1" --> "many" Classroom : contains
    School "1" --> "many" Subject : contains
    School "1" --> "many" Evaluation : contains
    School "1" --> "many" Grade : contains
    School "1" --> "many" TeacherAssignment : contains
    School "1" --> "many" Import : contains

    Level "1" --> "many" User : assigned level
    Level "1" --> "many" Classroom : groups

    AcademicYear "1" --> "many" Classroom : opens
    AcademicYear "1" --> "many" Evaluation : schedules

    Classroom "1" --> "many" StudentProfile : hosts
    Classroom "many" --> "many" Subject : classroom_subject
    Classroom "1" --> "many" Evaluation : receives

    Subject "1" --> "many" Evaluation : evaluated in
    Subject "1" --> "many" TeacherAssignment : taught in

    User "1" --> "0..1" StudentProfile : student profile
    User "1" --> "0..1" ParentProfile : parent profile
    User "1" --> "0..1" TeacherProfile : teacher profile
    User "1" --> "0..1" AdministrationProfile : admin profile

    User "1" --> "many" TeacherAssignment : teacher
    User "1" --> "many" Evaluation : creates
    User "1" --> "many" Grade : receives as student
    User "1" --> "many" GradeComment : writes as teacher
    User "1" --> "many" Notification : receives
    User "1" --> "many" Import : launches
    User "1" --> "many" AuditLog : generates

    User "many" --> "many" User : parent_student

    TeacherAssignment "many" --> "1" Classroom : assigned to
    TeacherAssignment "many" --> "1" Subject : assigned to
    TeacherAssignment "many" --> "1" User : teacher

    Evaluation "many" --> "1" Classroom : belongs to
    Evaluation "many" --> "1" Subject : belongs to
    Evaluation "many" --> "1" AcademicYear : belongs to
    Evaluation "many" --> "1" User : teacher
    Evaluation "1" --> "many" Grade : produces

    Grade "many" --> "1" Evaluation : result of
    Grade "many" --> "1" User : student
    Grade "1" --> "many" GradeComment : has
    Grade "1" --> "many" ReviewRequest : has

    GradeComment "many" --> "1" Grade : belongs to
    GradeComment "many" --> "1" User : teacher

    ReviewRequest "many" --> "1" Grade : concerns
    ReviewRequest "many" --> "1" User : student

    StudentProfile "many" --> "1" Classroom : belongs to
    StudentProfile "1" --> "1" User : extends
    ParentProfile "1" --> "1" User : extends
    TeacherProfile "1" --> "1" User : extends
    AdministrationProfile "1" --> "1" User : extends
```

## Relations importantes à comprendre

### 1. `User` est l'entité centrale

Un utilisateur peut être :

- `student`
- `parent`
- `teacher`
- `school_admin`
- `super_admin`

Le rôle est géré par le système de permissions, tandis que les données complémentaires sont stockées dans :

- `StudentProfile`
- `ParentProfile`
- `TeacherProfile`
- `AdministrationProfile`

### 2. Relation parent - enfant

La relation parent/enfant est une relation `many-to-many` entre `User` et `User` via la table pivot `parent_student`.

Cela permet :

- à un parent de suivre plusieurs enfants
- à un enfant d'être lié à un ou plusieurs parents

### 3. Chaîne pédagogique principale

Le flux métier principal est :

`School` → `Level` → `Classroom` → `Subject` → `Evaluation` → `Grade`

Puis :

- `Grade` peut recevoir des `GradeComment`
- `Grade` peut recevoir des `ReviewRequest`

### 4. Affectation des enseignants

`TeacherAssignment` relie :

- un enseignant
- une classe
- une matière

Cette table explique qui enseigne quoi, et dans quelle classe.

### 5. Administration et suivi

Les entités suivantes servent au pilotage :

- `Notification`
- `Import`
- `AuditLog`

Elles permettent respectivement :

- d'informer les utilisateurs
- d'importer des données
- de tracer les actions importantes
