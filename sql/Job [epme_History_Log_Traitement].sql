

--
-- Definition for Job [epme_History_Log_Traitement] : 
--

USE [msdb]
GO

/****** Object:  Job [epme_History_Log_Traitement]    Script Date: 24/04/2020 05:59:49 ******/
BEGIN TRANSACTION
DECLARE @ReturnCode INT
SELECT @ReturnCode = 0
/****** Object:  JobCategory [[Uncategorized (Local)]]]    Script Date: 24/04/2020 05:59:49 ******/
IF NOT EXISTS (SELECT name FROM msdb.dbo.syscategories WHERE name=N'[Uncategorized (Local)]' AND category_class=1)
BEGIN
EXEC @ReturnCode = msdb.dbo.sp_add_category @class=N'JOB', @type=N'LOCAL', @name=N'[Uncategorized (Local)]'
IF (@@ERROR <> 0 OR @ReturnCode <> 0) GOTO QuitWithRollback

END

DECLARE @jobId BINARY(16)
EXEC @ReturnCode =  msdb.dbo.sp_add_job @job_name=N'epme_History_Log_Traitement', 
    @enabled=1, 
    @notify_level_eventlog=0, 
    @notify_level_email=0, 
    @notify_level_netsend=0, 
    @notify_level_page=0, 
    @delete_level=0, 
    @description=N'No description available.', 
    @category_name=N'[Uncategorized (Local)]', 
    @owner_login_name=N'sa', @job_id = @jobId OUTPUT
IF (@@ERROR <> 0 OR @ReturnCode <> 0) GOTO QuitWithRollback
/****** Object:  Step [Log Connexion]    Script Date: 24/04/2020 05:59:49 ******/
EXEC @ReturnCode = msdb.dbo.sp_add_jobstep @job_id=@jobId, @step_name=N'Log Connexion', 
    @step_id=1, 
    @cmdexec_success_code=0, 
    @on_success_action=3, 
    @on_success_step_id=0, 
    @on_fail_action=2, 
    @on_fail_step_id=0, 
    @retry_attempts=0, 
    @retry_interval=0, 
    @os_run_priority=0, @subsystem=N'TSQL', 
    @command=N'INSERT INTO [Historique_e_pme].[dbo].[LOG_CONNEXION_SAVE] (ID_UT
      ,CODE_LG_CNX
      ,ACTION_LG_CNX
      ,LOG_LG_CNX
      ,IP
      ,BROWER
      ,SYSTEM
      ,DATE_INSERT_LG_CNX
      ,DATE_MODIF_LG_CNX
      ,ETAT_LG_CNX)
SELECT ID_UT
      ,CODE_LG_CNX
      ,ACTION_LG_CNX
      ,LOG_LG_CNX
      ,IP
      ,BROWER
      ,SYSTEM
      ,DATE_INSERT_LG_CNX
      ,DATE_MODIF_LG_CNX
      ,ETAT_LG_CNX FROM [e_pme_db_v2].[dbo].[LOG_CONNEXION] ORDER BY ID_LG_CNX ASC

DELETE FROM [e_pme_db_v2].[dbo].[LOG_CONNEXION]', 
    @database_name=N'master', 
    @flags=0
IF (@@ERROR <> 0 OR @ReturnCode <> 0) GOTO QuitWithRollback
/****** Object:  Step [Log traitement]    Script Date: 24/04/2020 05:59:49 ******/
EXEC @ReturnCode = msdb.dbo.sp_add_jobstep @job_id=@jobId, @step_name=N'Log traitement', 
    @step_id=2, 
    @cmdexec_success_code=0, 
    @on_success_action=1, 
    @on_success_step_id=0, 
    @on_fail_action=2, 
    @on_fail_step_id=0, 
    @retry_attempts=0, 
    @retry_interval=0, 
    @os_run_priority=0, @subsystem=N'TSQL', 
    @command=N'INSERT INTO [Historique_e_pme].[dbo].[LOG_TRAITEMENT_SAVE] (ID_UT
      ,CODE_LG_TR
      ,ACTION_LG_TR
      ,LOG_LG_TR
      ,IP
      ,BROWER
      ,SYSTEM
      ,DATE_INSERT_LG_TR)
SELECT ID_UT
      ,CODE_LG_TR
      ,ACTION_LG_TR
      ,LOG_LG_TR
      ,IP
      ,BROWER
      ,SYSTEM
      ,DATE_INSERT_LG_TR FROM [e_pme_db_v2].[dbo].[LOG_TRAITEMENT] ORDER BY ID_LG_TR ASC

DELETE FROM [e_pme_db_v2].[dbo].[LOG_TRAITEMENT]', 
    @database_name=N'master', 
    @flags=0
IF (@@ERROR <> 0 OR @ReturnCode <> 0) GOTO QuitWithRollback
EXEC @ReturnCode = msdb.dbo.sp_update_job @job_id = @jobId, @start_step_id = 1
IF (@@ERROR <> 0 OR @ReturnCode <> 0) GOTO QuitWithRollback
EXEC @ReturnCode = msdb.dbo.sp_add_jobschedule @job_id=@jobId, @name=N'Shedules Connexion &Traitement', 
    @enabled=1, 
    @freq_type=16, 
    @freq_interval=1, 
    @freq_subday_type=1, 
    @freq_subday_interval=0, 
    @freq_relative_interval=0, 
    @freq_recurrence_factor=3, 
    @active_start_date=20200423, 
    @active_end_date=99991231, 
    @active_start_time=1, 
    @active_end_time=235959, 
    @schedule_uid=N'd8bc34eb-f472-4dba-95b5-a37275538c8a'
IF (@@ERROR <> 0 OR @ReturnCode <> 0) GOTO QuitWithRollback
EXEC @ReturnCode = msdb.dbo.sp_add_jobserver @job_id = @jobId, @server_name = N'(local)'
IF (@@ERROR <> 0 OR @ReturnCode <> 0) GOTO QuitWithRollback
COMMIT TRANSACTION
GOTO EndSave
QuitWithRollback:
    IF (@@TRANCOUNT > 0) ROLLBACK TRANSACTION
EndSave:

GO