<div class="container my-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card profile-sidebar mb-4">
                <div class="card-body text-center">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle fa-5x"></i>
                    </div>
                    <h4 class="mt-3"><?= htmlspecialchars($user['name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    <div class="mt-3">
                        <a href="<?= baseUrl() ?>/profile" class="btn btn-outline-primary btn-sm w-100 mb-2">
                            <i class="fas fa-user me-1"></i> الملف الشخصي
                        </a>
                        <a href="<?= baseUrl() ?>/settings" class="btn btn-outline-secondary btn-sm w-100">
                            <i class="fas fa-cog me-1"></i> الإعدادات
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reading History Content -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> سجل القراءة</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($readingHistory)): ?>
                        <p class="text-muted text-center">لا يوجد سجل قراءة حتى الآن</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>السورة</th>
                                        <th>الآية</th>
                                        <th>التاريخ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($readingHistory as $history): ?>
                                        <tr>
                                            <td><?= $history['surah_number'] ?></td>
                                            <td><?= $history['ayah_number'] ?></td>
                                            <td><?= date('Y-m-d H:i', strtotime($history['timestamp'])) ?></td>
                                            <td>
                                                <a href="<?= baseUrl() ?>/quran/<?= $history['surah_number'] ?>/<?= $history['ayah_number'] ?>" class="btn btn-sm btn-outline-primary" title="الذهاب إلى الآية">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="Page navigation" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <!-- Previous Button -->
                                    <li class="page-item <?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= baseUrl() ?>/profile/history?page=<?= $currentPage - 1 ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    
                                    <!-- Page Numbers -->
                                    <?php
                                    $startPage = max(1, $currentPage - 2);
                                    $endPage = min($totalPages, $startPage + 4);
                                    if ($endPage - $startPage < 4) {
                                        $startPage = max(1, $endPage - 4);
                                    }
                                    
                                    for ($i = $startPage; $i <= $endPage; $i++):
                                    ?>
                                        <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= baseUrl() ?>/profile/history?page=<?= $i ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <!-- Next Button -->
                                    <li class="page-item <?= ($currentPage >= $totalPages) ? 'disabled' : '' ?>">
                                        <a class="page-link" href="<?= baseUrl() ?>/profile/history?page=<?= $currentPage + 1 ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 