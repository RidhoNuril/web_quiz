<?php 
    function count_quiz_subject($subject_id){
        include 'db.php';

        $stmt = $db->prepare("SELECT subject_id FROM quiz WHERE subject_id=?");
        $stmt->bind_param("i",$subject_id);
        $stmt->execute();

        // Jumlah quiz per subject
        $response = $stmt->get_result()->num_rows;

        return $response;
    }


    function get_all_data_subject(){
        include 'db.php';

        $stmt = $db->prepare("SELECT * FROM subject");
        $stmt->execute();

        $results = $stmt->get_result();

        if($results->num_rows > 0){
            while($row = $results->fetch_assoc()){
                $response[] = [
                    'subject_id' => $row['subject_id'],
                    'thumbnail' => $row['thumbnail'],
                    'subject_name' => $row['subject_name'],
                    'subject_desc' => $row['subject_desc']
                ];
            }
        }else{
            $response = [];
        }

        return $response;
    }

    function insert_subject($subject_name, $subject_desc, $tmp_name, $thumbnail){
        include 'includes/db.php';

        if($subject_name && $subject_desc != ''){
                
                if($thumbnail != ''){

                    $rand_image = rand().'-'.$thumbnail;
                    $insert_dir = "assets/image/$rand_image";
                    move_uploaded_file($tmp_name, $insert_dir);

                    $set_thumbnail = $rand_image;
                }else{
                    $set_thumbnail = 'default_thumbnail.png';
                }

                $stmt = $db->prepare("INSERT INTO subject (thumbnail, subject_name, subject_desc) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $set_thumbnail, $subject_name, $subject_desc);
                $stmt->execute();

                $response = [
                    'status' => 'success',
                    'message' => 'Subject berhasil ditambahkan',
                    'redirect' => 'data_subjects.php'
                ];
            
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Semua field wajib diisi',
            ];
        }

        return $response;
    }

    function update_subject($subject_id, $subject_name, $subject_desc, $tmp_name, $thumbnail){
        include 'includes/db.php';

        if($subject_id && $subject_name && $subject_desc != ''){

            $subject = $db->prepare("SELECT thumbnail FROM subject WHERE subject_id=?");
            $subject->bind_param("i", $subject_id);
            $subject->execute();
            $result = $subject->get_result();
            $thumbnail_name_db = $result->fetch_assoc();

            if($result->num_rows > 0){
                
                if($thumbnail != ''){
                    $delete_dir = "assets/image/$thumbnail_name_db[thumbnail]";
                    unlink($delete_dir);

                    
                    $rand_image = rand().'-'.$thumbnail;
                    $update_dir = "assets/image/$rand_image";
                    move_uploaded_file($tmp_name, $update_dir);

                    $set_thumbnail = $rand_image;
                }else{
                    $set_thumbnail = $thumbnail_name_db['thumbnail'];
                }

                $stmt = $db->prepare("UPDATE subject SET thumbnail=?, subject_name=?, subject_desc=? WHERE subject_id=?");
                $stmt->bind_param("sssi", $set_thumbnail, $subject_name, $subject_desc, $subject_id);
                $stmt->execute();

                $response = [
                    'status' => 'success',
                    'message' => 'Subject berhasil diedit',
                    'redirect' => 'data_subjects.php'
                ];

            }else{
                $response = [
                    'status' => 'error',
                    'message' => 'Subject tidak ditemukan'
                ];
            }
            
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Semua field wajib diisi',
            ];
        }

        return $response;
    }

    function delete_subject($id){
        include 'includes/db.php';

        if($id != ''){
            $subject = $db->prepare("SELECT thumbnail FROM subject WHERE subject_id=?");
            $subject->bind_param("i",$id);
            $subject->execute();
            $result = $subject->get_result()->fetch_assoc();

            if($result['thumbnail'] != 'default_thumbnail.png'){
                $delete_file = "assets/image/$result[thumbnail]";
                unlink($delete_file);
            }

            $stmt = $db->prepare("DELETE FROM subject WHERE subject_id=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'message' => 'Subject berhasil dihapus'
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Id subject tidak ditemukan'
            ];
        }

        return $response;
    }

    function insert_quiz($subject_id, $judul_quiz){
        include 'includes/db.php';

        if($judul_quiz != ''){
            $stmt = $db->prepare("INSERT INTO quiz (subject_id, title) VALUES (?, ?)");
            $stmt->bind_param("is", $subject_id, $judul_quiz);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'message' => 'Quiz berhasil dibuat',
                'redirect' => 'data_quiz.php?id='.$subject_id.''
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Judul quiz wajib diisi'
            ];
        }

        return $response;
    }

    function update_quiz($id_quiz, $subject_id, $judul_quiz){
        include 'includes/db.php';

        if($judul_quiz != ''){
            $stmt = $db->prepare("UPDATE quiz SET subject_id=?, title=? WHERE id_quiz=?");
            $stmt->bind_param("isi", $subject_id, $judul_quiz, $id_quiz);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'message' => 'Quiz berhasil diupdate',
                'redirect' => 'data_quiz.php?id='.$subject_id.''
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Judul quiz wajib diisi'
            ];
        }

        return $response;
    }

    function delete_quiz($id){
        include 'includes/db.php';

        if($id != ''){
            $stmt = $db->prepare("DELETE FROM quiz WHERE id_quiz=?");
            $stmt->bind_param("i",$id);
            $stmt->execute();

            $response = [
                'status' => 'success',
                'message' => 'Quiz berhasil dihapus'
            ];
        }else{
            $response = [
                'status' => 'error',
                'message' => 'Id quiz tidak ditemukan'
            ];
        }

        return $response;
    }
?>