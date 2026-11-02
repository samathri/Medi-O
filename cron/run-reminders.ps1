$ErrorActionPreference = 'Stop'
$appRoot = Split-Path -Parent $PSScriptRoot
$phpExe = 'C:\xampp\php\php.exe'
$logDirectory = Join-Path $appRoot 'logs'
New-Item -ItemType Directory -Path $logDirectory -Force | Out-Null
& $phpExe (Join-Path $PSScriptRoot 'send-reminders.php') *>> (Join-Path $logDirectory 'reminder-task.log')
exit $LASTEXITCODE
