<?php
/**
 * 获取上传文件信息
 * @param  int $id 文件ID
 * @return string
 * @author jry <598821125@qq.com>
 */
function get_upload_info($id, $field)
{
    $upload_info = D('Admin/Upload')->where('status = 1')->find($id);
    if ($field) {
        if (!$upload_info[$field]) {
            return $upload_info['id'];
        } else {
            return $upload_info[$field];
        }
    }
    return $upload_info;
}