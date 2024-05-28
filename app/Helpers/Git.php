<?php

namespace App\Helpers;

class Git
{

    public static function getGitInfo()
    {
        $gitBasePath = base_path() . '/.git';

        $gitStr = file_get_contents($gitBasePath . '/HEAD');
        $gitBranchName = rtrim(preg_replace("/(.*?\/){2}/", '', $gitStr));
        $gitPathBranch = $gitBasePath . '/refs/heads/' . $gitBranchName;
        $gitHash = file_get_contents($gitPathBranch);
        $gitDate = date(DATE_ATOM, filemtime($gitPathBranch));

        return [
            'branch' => $gitBranchName,
            'hash' => $gitHash,
            'date' => $gitDate,
        ];
    }
}
