' Runs the reminder dispatcher with no console window (used by the Windows task).
Set fso = CreateObject("Scripting.FileSystemObject")
Set sh  = CreateObject("WScript.Shell")
cronDir = fso.GetParentFolderName(WScript.ScriptFullName)
appRoot = fso.GetParentFolderName(cronDir)
logDir  = appRoot & "\logs"
If Not fso.FolderExists(logDir) Then fso.CreateFolder logDir
cmd = "cmd /c """"C:\xampp\php\php.exe"" """ & cronDir & "\send-reminders.php"" >> """ & logDir & "\reminder-task.log"" 2>&1"""
sh.Run cmd, 0, True
