                                <?php

                                $total_success_rate = 0;
                                $total_respondents = 0;

                                // Ambil task berdasarkan survei
                                $tasks = [];
                                $q_task = mysqli_query($conn, "SELECT DISTINCT id_task_skenario, task_skenario FROM v_respon_task_skenario WHERE id_survei = $id_survei ORDER BY id_task_skenario");
                                while ($row = mysqli_fetch_assoc($q_task)) {
                                    $tasks[$row['id_task_skenario']] = $row['task_skenario'];
                                }

                                // Ambil data responden dan task mereka
                                $respondents = [];
                                $q_responden = mysqli_query($conn, "SELECT DISTINCT id_responden, nama_responden FROM v_respon_task_skenario WHERE id_survei = $id_survei");

                                while ($r = mysqli_fetch_assoc($q_responden)) {
                                    $id_responden = $r['id_responden'];
                                    $nama = $r['nama_responden'];

                                    $respondents[$id_responden] = [
                                        'name' => $nama,
                                        'tasks_completed' => [],
                                        'success' => 0,
                                        'total' => 0
                                    ];

                                    $q_tasks = mysqli_query($conn, "SELECT id_task_skenario, task_sukses FROM v_respon_task_skenario WHERE id_survei = $id_survei AND id_responden = $id_responden");

                                    while ($t = mysqli_fetch_assoc($q_tasks)) {
                                        $respondents[$id_responden]['total']++;
                                        if ($t['task_sukses'] == 1) {
                                            $respondents[$id_responden]['success']++;
                                            $respondents[$id_responden]['tasks_completed'][] = $t['id_task_skenario'];
                                        }
                                    }
                                }

                                $total_respondents = count($respondents);
                                $taskNumber = 1;
                                ?>

                                <!-- Container Keterangan -->
                                <div class="alert alert-warning shadow">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i>Keterangan:</h5>
                                    1 = <span class="text-success"><strong>Berhasil</strong></span><br>
                                    0 = <span class="text-danger"><strong>Gagal</strong></span>
                                </div>

                                <table id="successrate" class="table table-bordered display nowrap table-striped" style="width:100%">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Responden</th>
                                            <?php foreach ($tasks as $id_task => $nama_task): ?>
                                                <th>T<sub><?= $taskNumber++; ?></sub> (<?php echo ucwords(htmlspecialchars($nama_task)); ?>)</th>
                                            <?php endforeach; ?>
                                            <th>Task Berhasil</th>
                                            <th>Total Task</th>
                                            <th>Success Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($respondents as $id => $respondent):
                                            $success_rate = ($respondent['total'] > 0) ? round(($respondent['success'] / $respondent['total']) * 100, 2) : 0;
                                            $total_success_rate += $success_rate;

                                            // Ambil data pekerjaan
                                            $query_pekerjaan = "SELECT pekerjaan FROM v_respon_task_skenario WHERE id_responden = $id AND id_survei = $id_survei LIMIT 1";
                                            $result_pekerjaan = mysqli_query($conn, $query_pekerjaan);
                                            $pekerjaan = mysqli_fetch_assoc($result_pekerjaan);
                                        ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo htmlspecialchars($respondent['name']); ?></td>

                                                <?php foreach ($tasks as $id_task => $nama_task): ?>
                                                    <td class="<?php echo in_array($id_task, $respondent['tasks_completed']) ? 'completed fw-bold text-center' : 'text-center'; ?>">
                                                        <?php echo in_array($id_task, $respondent['tasks_completed']) ? ' (1) <span class="text-success">✓</span>' : ' (0) <span class="text-danger">✗</span>'; ?>
                                                    </td>
                                                <?php endforeach; ?>

                                                <td class="text-center"><?php echo $respondent['success']; ?></td>
                                                <td class="text-center"><?php echo $respondent['total']; ?></td>
                                                <td class="text-center"><?php echo $success_rate; ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <th colspan="<?php echo count($tasks) + 4; ?>"><strong>Rata-rata Success Rate</strong></th>
                                            <th colspan=""><strong><?php echo $total_respondents > 0 ? round($total_success_rate / $total_respondents, 2) : 0; ?>%</strong></th>
                                        </tr>
                                    </tfoot>
                                </table>