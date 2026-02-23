<?php

// Download Module - Case Study Integration
$download_categories = array(
    1 => 'Research',
    2 => 'Case Study',
    3 => 'Medical Study'
);

// Get all published downloads from database
$moduleTablename = "tbl_download";
$records = Download::find_by_sql("SELECT * FROM " . $moduleTablename . " WHERE status = 1 ORDER BY sortorder DESC");

$downloadRows = '';
$sn = 1;

if (!empty($records)) {
    foreach ($records as $key => $record) {
        $file_ext = strtoupper(pathinfo($record->image, PATHINFO_EXTENSION));
        $file_date = !empty($record->case_date) ? date('jS F Y', strtotime($record->case_date)) : 'N/A';
        $file_path = BASE_URL . "images/download/docs/" . $record->image;

        $downloadRows .= '
                                <tr data-category="' . $record->category . '">
                                    <th scope="row">' . $sn++ . '</th>
                                    <td>' . $record->title . '</td>
                                    <td>' . $file_date . '</td>
                                    <td>' . $file_ext . '</td>
                                    <td><button><a href="' . $file_path . '" download><img src="template/web/assets/img/icon/download.png" alt="download">Download</a></button></td>
                                </tr>';
    }
} else {
    $downloadRows = '
                                <tr>
                                    <td colspan="5" class="text-center">No downloads available</td>
                                </tr>';
}

$case = '
        <section class="ul-about ul-section-spacing wow animate__fadeInUp">
            <div class="ul-container">
                <div class="row justify-content-center gy-4">
                    <div class="col-md-4">
                        <div class="case-inner">
                            <h6 class="mt-2 me-2">Category:</h6>
                            <select class="form-select" id="categoryFilter" aria-label="Filter by category" onchange="filterByCategory(this.value)">
                                <option value="">All Categories</option>
                                <option value="1">Research</option>
                                <option value="2">Case Study</option>
                                <option value="3">Medical Study</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center gy-4">

                    <div class="col-md-8">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">S.N</th>
                                    <th scope="col">Case Study</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Download</th>
                                </tr>
                            </thead>
                            <tbody id="downloadTableBody">
                                ' . $downloadRows . '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        
        <script>
            function filterByCategory(categoryId) {
                const rows = document.querySelectorAll("#downloadTableBody tr");
                let visibleCount = 0;
                
                rows.forEach(row => {
                    if (categoryId === "" || categoryId === undefined || categoryId === null) {
                        row.style.display = "";
                        visibleCount++;
                        // Update serial number
                        row.querySelector("th").textContent = visibleCount;
                    } else {
                        const rowCategory = row.getAttribute("data-category");
                        if (rowCategory === categoryId) {
                            row.style.display = "";
                            visibleCount++;
                            // Update serial number
                            row.querySelector("th").textContent = visibleCount;
                        } else {
                            row.style.display = "none";
                        }
                    }
                });
            }
        </script>';

$jVars['module:case-study'] = $case;
